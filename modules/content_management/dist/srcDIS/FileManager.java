/** 
 * Jdk platform : 1.8 
 */

/** 
 * SVN version 120
 */


package com.dis;

import java.io.*;
import java.lang.reflect.InvocationTargetException;
import java.security.AccessController;
import java.security.PrivilegedActionException;
import java.security.PrivilegedExceptionAction;
import org.apache.commons.codec.binary.Base64;

import java.nio.file.Files;
import java.nio.file.Paths;

/**
 * FileManager class manages the exchange of files between the applet and the workstation
 * @author Laurent Giovannoni
 */
public class FileManager {
    
    /**
    * Creates the tmp dir
    * @param path path to the tmp dir
    */
    public String createUserLocalDirTmp(String path, String os) throws IOException {
        File file=new File(path);
        String msg = "";
        if (!file.exists()) {
            System.out.println("directory " + path + " not exists so the applet will create it");
            if (file.mkdir()) {
                System.out.println("Directory: " + path + " created");
            } else {
                System.out.println("Directory: " + path + " not created");
                msg = "ERROR";
            }
        } else {
            System.out.println("directory " + path + " already exists");
        }
        if (!file.setReadable(true, false)) {
            System.out.println("set permission readable failed on : " + path);
            msg = "ERROR";
        }
        if (!file.setWritable(true, false) && !"win".equals(os)) {
            System.out.println("set permission writable failed on : " + path);
            msg = "ERROR";
        }
        if (!file.setExecutable(true, false)) {
            System.out.println("set permission executable failed on : " + path);
            msg = "ERROR";
        }
        return msg;
    }
    
    /**
    * Controls the existance of psExec file in tmp dir
    * @param path path to the tmp dir
    * @return boolean
    */
    public boolean isPsExecFileExists(String path) throws IOException {
        File file=new File(path);
        if (!file.exists()) {
            System.out.println("psExec on path " + path + " not exists so the applet will create it");
            return false;
        } else {
            System.out.println("psExec on path " + path + " already exists");
            return true;
        }
    }
    
    /**
    * Creates the template sended by Maarch file in tmp dir
    * @param encodedContent the file to create
    * @param pathTofile directory of the file to create
    * @return boolean
    */
    public boolean createFile(String encodedContent, final String pathTofile) throws IOException, PrivilegedActionException{
        final byte[] decodedBytes = Base64.decodeBase64(encodedContent);
        AccessController.doPrivileged(new PrivilegedExceptionAction() {
                public Object run() throws IOException {
                    FileOutputStream fos = new FileOutputStream(pathTofile);
                    fos.write(decodedBytes);
                    fos.close();
                    File file = new File(pathTofile);
                    if (!file.setReadable(true, false)) {
                        System.out.println("set permission readable failed on : " + pathTofile);
                    }
                    if (!file.setWritable(true, false)) {
                        System.out.println("set permission writable failed on : " + pathTofile);
                    }
                    if (!file.setExecutable(true, false)) {
                        System.out.println("set permission executable failed on : " + pathTofile);
                    }
                    return fos;
                }
            }
        );
        return true;
    }
    
    /**
    * Creates the bat file to launch te editor of te template
    * @param pathToBatFile path to the bat file
    * @param pathToFileToLaunch path to the file to launch
    * @param fileToLaunch name of the file to launch
    * @param os os of the workstation
    * @return boolean
    */
    public boolean createBatFile(
            final String pathToBatFile, 
            final String pathToFileToLaunch, 
            final String fileToLaunch, 
            final String os
            ) throws IOException, PrivilegedActionException {
        final Writer out;
        if ("win".equals(os)) {
            out = new OutputStreamWriter(new FileOutputStream(pathToBatFile), "CP850");
        } else {
            out = new OutputStreamWriter(new FileOutputStream(pathToBatFile), "utf-8");
        }
        AccessController.doPrivileged(new PrivilegedExceptionAction() {
                public Object run() throws IOException {
                    if ("win".equals(os)) {
                        if (fileToLaunch.contains(".odt") || fileToLaunch.contains(".ods")) {
                            //out.write("start /WAIT SOFFICE.exe -env:UserInstallation=file:///" 
                            //    + pathToFileToLaunch.replace("\\", "/")  + " \"" + pathToFileToLaunch + fileToLaunch + "\"");
                            out.write("start /WAIT SOFFICE.exe -env:UserInstallation=$SYSUSERCONFIG \"" + pathToFileToLaunch + fileToLaunch + "\"");
                        } else {
                            out.write("start /WAIT \"\" \"" + pathToFileToLaunch + fileToLaunch + "\"");
                        }
                    } else if ("mac".equals(os)) {
                        out.write("open -W " + pathToFileToLaunch + fileToLaunch);
                    } else if ("linux".equals(os)) {
                        out.write("libreoffice " + pathToFileToLaunch + fileToLaunch + " || ooffice " + pathToFileToLaunch + fileToLaunch +"& wait");
                    }
                    out.close();
                    File file = new File(pathToBatFile);
                    if (!file.setReadable(true, false)) {
                        System.out.println("set permission readable failed on : " + pathToBatFile);
                    }
                    if (!file.setWritable(true, false)) {
                        System.out.println("set permission writable failed on : " + pathToBatFile);
                    }
                    if (!file.setExecutable(true, false)) {
                        System.out.println("set permission executable failed on : " + pathToBatFile);
                    }
                    
                    return out;
                }
            }
        );
        return true;
    }
    
    /**
    * Encodes a file in base64
    * @param fichier path to the file to encode
    * @return string
    * @throws java.lang.Exception
    */
    public static String encodeFile(String fichier) throws Exception {
        byte[] buffer = readFile(fichier);
        byte[] encodedBytes = Base64.encodeBase64(buffer);
        return new String(encodedBytes);
    }
    
    /**
    * Reads a file
    * @param filename path to the file to read
    * @return byte
    */
    private static byte[] readFile(String filename) throws IOException {
        byte[] fileToEncode = Files.readAllBytes(Paths.get(filename));
        return fileToEncode;
    }
    
    /**
    * Launchs a command to execute
    * @param launchCommand the command to launch
    * @return process
    */
    public Process launchApp(final String launchCommand) throws PrivilegedActionException {
        return (Process) AccessController.doPrivileged(
            new PrivilegedExceptionAction() {
                public Object run() throws IOException {
                    return Runtime.getRuntime().exec(launchCommand);
                }
            }
        );
    }
    
    /**
    * Retrieves the right program to edit the template with his extension
    * @param ext extension of the template
    * @return string
    */
    public String findGoodProgramWithExt(String ext) {
        String program = "";
        if ((ext.equalsIgnoreCase("docx") || ext.equalsIgnoreCase("doc") || ext.equalsIgnoreCase("docm"))) {
            program = "winword.exe";
        } else if (ext.equalsIgnoreCase("xlsx") || ext.equalsIgnoreCase("xls") || ext.equalsIgnoreCase("xlsm")) {
            program = "excel.exe";
        } else if (ext.equalsIgnoreCase("pptx") || ext.equalsIgnoreCase("ppt") || ext.equalsIgnoreCase("pptm") || ext.equalsIgnoreCase("ppsm")) {
            program = "powerpnt.exe";
        } else {
            program = "soffice.exe";
        }
        
        return program;
    }
    
    /**
    * Retrieves the path of a program in the registry
    * @param program name of the program
    * @return string
    */
    public String findPathProgramInRegistry(String program) throws IllegalArgumentException, IllegalAccessException, InvocationTargetException {
        String path;
        path =  WinRegistry.readString (
                WinRegistry.HKEY_LOCAL_MACHINE,                                                     //HKEY
                "SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\App Paths\\" + program,              //Key
                "");
        return "\"" + path + "\"";
    }
    
    /**
    * Retrieves the right options to edit the template
    * @param ext extension of the template
    * @return string
    */
    public String findGoodOptionsToEdit(String ext) {
        String options = "";
        if (
                "docx".equals(ext.toLowerCase()) || 
                "doc".equals(ext.toLowerCase()) ||
                "docm".equals(ext.toLowerCase()) 
        ) {
            options = " /n /dde ";
        } else if (
                "xlsx".equals(ext.toLowerCase()) || 
                "xls".equals(ext.toLowerCase()) ||
                "xlsm".equals(ext.toLowerCase()) 
        ) {
            options = " /x ";
        } else if (
                "pptx".equals(ext.toLowerCase()) || 
                "ppt".equals(ext.toLowerCase()) ||
                "pptm".equals(ext.toLowerCase()) ||
                "ppsm".equals(ext.toLowerCase()) 
        ) {
            options = " ";
        } else {
            options = " -env:UserInstallation=$SYSUSERCONFIG ";
        }
        
        return options;
    }
    
    /**
    * Deletes file in the tmp dir
    * @param directory path of the tmp dir
    * @param pattern pattern of files to delete
    */
    public static void deleteFilesOnDir (String directory, String pattern) throws IOException {
        File dir = new File(directory);
        File[] directoryListing = dir.listFiles();
        if (directoryListing != null) {
          for (File child : directoryListing) {
            System.out.println("a file : " + child);
            if (child.toString().contains(pattern)) {
                System.out.println("a file with pattern : " + child);
                child.delete();
            }
          }
        }
    }
    
}