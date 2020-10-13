import java.io.File;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;
import java.util.LinkedList;
import java.util.Map;
import java.util.HashMap;
import java.util.StringTokenizer;
/*
Script that, given a directory, analizes every subdirectoy and evry file. In this specific implementation, due to the structure of 
the 'music' directory, the script:
 - recognizes every artist;
 - for each artist, it builds a map made by his albums;
 - for each album, it builds an array of songs;
 - each song is formatted as an array of 2 elements: the tile and the number of the sung within the album

Then, if the 'fileEnable' flag is set to 'true', the script will create a file containing 'INSERT' queries in order to populate
the database with songs element, whilst printing them. In this case songs are arrays of 5 elements {title, album, number, author, genre}. Otherwise, 
flag set to 'false' or omitted, the script will just print all the songs it found.
*/

class MusicReader {

    private static LinkedList<String[]> globalsongs = new LinkedList<String[]>();
    private static Double samegenre = 0.12345;

    private File initDir;
    private boolean fileEnable;
    private String extension = ".mp3";
    private int extension_length = extension.length();

    public MusicReader(File initDir) {
        if (initDir.isDirectory())
            this.initDir = initDir;
        this.fileEnable = false;
    }

    public MusicReader(File initDir, boolean fileEnable) {
        if (initDir.isDirectory())
            this.initDir = initDir;
        this.fileEnable = fileEnable;
    }

    private void fileRenamer(File file, String newname){
        String path = file.getAbsolutePath();
        String regex = "[0-9]{2}_";
        String filename = file.getName();
        if (file.isDirectory() || filename.length() < 4)
            return;
        if (filename.substring(0, 3).matches(regex)){
            newname = path.replace(filename, newname) + ".mp3";
            File newfile = new File(newname);
            file.renameTo(newfile);
        }
        return;
    }

    private String[] nameParser(File file) {
        String[] tokens = { "", // ordinal number
                "" // name
        };
        String filename = file.getName();
        if (!file.isDirectory() && !filename.substring(filename.length() - extension_length).equals(extension))
            return null;
        if (!file.isDirectory() && filename.substring(filename.length() - extension_length).equals(extension)) {
            String tok = "";
            int begin = 0;
            if (filename.substring(0, 1).equals("0"))
                begin = 1;
            if (filename.substring(0,2).matches("[0-9]{2}")){
                tok = filename.substring(begin, 2);

            filename = filename.substring(3, filename.length());
            }
            tokens[0] = tok;
        }
        tokens[1] = filename.replace("\''", "_").replace(extension, "");
        fileRenamer(file, tokens[1]);
        return tokens;
    }

    private HashMap<String, LinkedList<String[]>> mapAlbumSongs(File artist) {
        HashMap<String, LinkedList<String[]>> buf = new HashMap<String, LinkedList<String[]>>();
        File[] dirList = artist.listFiles();
        for (File currdir : dirList) {
            if (currdir.isDirectory()) {
                LinkedList<String[]> parsedfiles = new LinkedList<String[]>();
                for (File filename : currdir.listFiles()) {
                    String[] parsedName = nameParser(filename);
                    if (parsedName != null)
                        parsedfiles.add(parsedName);
                }
                buf.put(nameParser(currdir)[1], parsedfiles);
            }
        }
        return buf;
    }

    private HashMap<String, HashMap<String, LinkedList<String[]>>> mapArtist() {
        HashMap<String, HashMap<String, LinkedList<String[]>>> map = new HashMap<String, HashMap<String, LinkedList<String[]>>>();
        File[] dirlist = this.initDir.listFiles();
        for (File artist : dirlist) {
            if (artist.isDirectory())
                map.put(nameParser(artist)[1], mapAlbumSongs(artist));
        }
        return map;
    }

    private void populateGlobalArray() {
        HashMap<String, HashMap<String, LinkedList<String[]>>> martist = mapArtist();
        for (String artist : martist.keySet()) {
            HashMap<String, LinkedList<String[]>> malbum = martist.get(artist);
            for (String album : malbum.keySet()) {
                for (String[] song : malbum.get(album)) {
                    // System.out.println(song[1] + song[0]);
                    String[] array = { song[1], // titolo
                            album, // album
                            song[0], // num
                            artist, // artita
                            samegenre.toString() // genere
                    };
                    globalsongs.add(array);
                }
                samegenre += samegenre / 2;
            }
        }
    }

    private void createQueryScript() {
        populateGlobalArray();
        File script = new File("script.txt");
        PrintWriter pw = null;
        try {
            pw = new PrintWriter(script);
        } catch (IOException e) {
            e.printStackTrace();
            return;
        }
        String line;
        for (String[] elem : globalsongs) {
            line = "INSERT INTO brano VALUES('" + elem[0] + "','" + elem[1] + "'," + elem[2] + ",'" + elem[3] + "','" + elem[4]
                    + "');";
            pw.println(line);
        }
        pw.close();
    }

    @Override
    public String toString() {
        String buf = "";
        HashMap<String, HashMap<String, LinkedList<String[]>>> martist = mapArtist();
        for (String artist : martist.keySet()) {
            buf += artist + "-->" + "{\n";
            HashMap<String, LinkedList<String[]>> malbum = martist.get(artist);
            for (String album : malbum.keySet()) {
                buf += album + "[\n";
                for (String[] song : malbum.get(album)) {
                    buf += song[0] + " " + song[1] + "\n";
                }
                buf += "\n]\n";
            }
            buf += "}\n";
        }
        // remove these line to enable the complete print of directory content
        buf = "";

        if (fileEnable) {
            createQueryScript();
            System.out.println("\nTHE SCRIPT HAS BEEN INITIALIZED WITH 'INSERT INTO' OF THIS 'VALUES'");
        } else {
            populateGlobalArray();
            System.out.println("\nTHESE WILL BE THE VALUES THE SCRIPT WILL BE INITISALIZED WITH");
            System.out.print("\nTO ENABLE THE CREATION OF THE SCRIPT, USE A \'true\' FLAG ");
            System.out.print("IN THE CONSTRUCTOR (a \'false\' one or the omission will bring ");
            System.out.println("again to this)");
        }
        for (String[] a : globalsongs) {
            System.out.println();
            System.out.println(a[0] + " " + a[1] + " " + a[2] + " " + a[3] + " " + a[4]);
        }
        return buf;
    }
}

public class Main {
    public static void main(String[] args) {
        File initDir = new File("../music");
        MusicReader mr = new MusicReader(initDir, true);
        System.out.println(mr.toString());
        return;
    }
}