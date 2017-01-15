/** 
 * Jdk platform : 1.8 
 */

/** 
 * SVN version 141
 */

package com.dis;

import java.applet.Applet;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStreamWriter;
import java.lang.reflect.InvocationTargetException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.security.PrivilegedActionException;
import java.util.HashSet;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.Set;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JApplet;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import netscape.javascript.JSException;
import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;
import netscape.javascript.JSObject;

import java.io.File;
import java.io.FileOutputStream;
import java.io.Writer;
import javax.swing.JDialog;
import javax.swing.JFrame;
import javax.swing.JOptionPane;


/**
 * DisCM class manages webservices between end user desktop and Maarch
 * @author DIS
 */
public class DisCM extends JApplet {
    
    //INIT PARAMETERS
    protected String url;
    protected String objectType;
    protected String objectTable;
    protected String objectId;
    protected String cookie;
    protected String clientSideCookies;
    protected String uniqueId;
    protected String userLocalDirTmp;
    protected String userMaarch;
    
    protected String messageStatus;
    
    Hashtable messageResult = new Hashtable();
    
    //XML PARAMETERS
    protected String status;
    protected String appPath;
    protected String appPath_convert;
    protected String fileContent;
    protected String fileContentVbs;
    protected String vbsPath;
    protected String fileContentExe;
    protected String useExeConvert;
    protected String fileExtension;
    protected String error;
    protected String endMessage;
    protected String os;
    
    protected String fileContentTosend;
    protected String pdfContentTosend;
    
    public MyLogger logger;
    
    public FileManager fM;
    public String fileToEdit;
    
    /**
     * Launch of the applet
     */
    public void init() throws JSException
    {
        System.out.println("----------BEGIN PARAMETERS----------");
        url = getParameter("url");
        objectType = getParameter("objectType");
        objectTable = getParameter("objectTable");
        objectId = getParameter("objectId");
        uniqueId = getParameter("uniqueId");
        cookie = getParameter("cookie");
        clientSideCookies = getParameter("clientSideCookies");
        userMaarch = getParameter("userMaarch");
        
        System.out.println("URL : " + url);
        System.out.println("OBJECT TYPE : " + objectType);
        System.out.println("OBJECT TABLE : " + objectTable);
        System.out.println("OBJECT ID : " + objectId);
        System.out.println("UNIQUE ID : " + uniqueId);
        System.out.println("COOKIE : " + cookie);
        System.out.println("CLIENTSIDECOOKIES : " + clientSideCookies);
        System.out.println("----------CONTROL PARAMETERS----------");
        
        if (
            isURLInvalid() || 
            isObjectTypeInvalid() || 
            isObjectTableInvalid() ||
            isObjectIdInvalid() ||
            isCookieInvalid()
        ) {
            System.out.println("PARAMETERS NOT OK ! END OF APPLICATION");
            //System.exit(0);
            try {
                this.getAppletContext().showDocument(new URL("error.html"));
                //Go to an appropriate error page
            } catch (Exception e) {
                //Nothing
            }
        }
        
        System.out.println("----------END PARAMETERS----------");
        try {
            this.editObject();
            this.destroy();
            this.stop();
            System.exit(0);
        } catch (Exception ex) {
            Logger.getLogger(DisCM.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    /**
     * Controls the url parameter
     * @return boolean
     */
    private boolean isURLInvalid() 
    {
        try {
            new URL(url).openConnection().connect();
            return false; //success
        } catch (MalformedURLException e) {
            System.out.println("the URL is not a valid form " + url);
        } catch (IOException e) {
            System.out.println("the connection couldn't be etablished " + url);
        }
        return true; //default is failure
    }
    
    /**
     * Controls the objectType parameter
     * @return boolean
     */
    private boolean isObjectTypeInvalid()
    {
        Set<String> whiteList= new HashSet<>();
        whiteList.add("template");
        whiteList.add("templateStyle");
        whiteList.add("attachmentVersion");
        whiteList.add("attachmentUpVersion");
        whiteList.add("resource");
        whiteList.add("attachmentFromTemplate");
        whiteList.add("attachment");
        whiteList.add("outgoingMail");
        if (whiteList.contains(objectType)) return false; //success
        System.out.println("ObjectType not in the authorized list " + objectType);
        return true; //default is failure
    }
    
    /**
     * Controls the objectTable parameter
     * @return boolean
     */
    private boolean isObjectTableInvalid()
    {
        Set<String> whiteList= new HashSet<>();
        whiteList.add("res_letterbox");
        whiteList.add("res_business");
        whiteList.add("res_x");
        whiteList.add("res_attachments");
        whiteList.add("mlb_coll_ext");
        whiteList.add("business_coll_ext");
        whiteList.add("res_version_letterbox");
        whiteList.add("res_version_business");
        whiteList.add("res_version_x");
        whiteList.add("res_view_attachments");
        whiteList.add("res_view");
        whiteList.add("res_view_letterbox");
        whiteList.add("res_view_business");
        whiteList.add("templates");
        if (whiteList.contains(objectTable)) return false; //success
        System.out.println("objectTable not in the authorized list " + objectTable);
        return true; //default is failure
    }
    
    /**
     * Controls the objectId parameter
     * @return boolean
     */
    private boolean isObjectIdInvalid()
    {
        if (objectId != null && objectId.length() > 0) return false; //success
        System.out.println("objectId is null or empty " + objectId);
        return true; //default is failure
    }
    
    /**
     * Controls the cookie parameter
     * @return boolean
     */
    private boolean isCookieInvalid()
    {
        if (cookie != null && cookie.length() > 0) return false; //success
        System.out.println("cookie is null or empty " + cookie);
        return true; //default is failure
    }
    
    public void createPDF(String docxFile, String directory, boolean isUnix) 
    {
        try {
            boolean conversion = true;
            String cmd = "";
            if (docxFile.contains(".odt") || docxFile.contains(".ods") || docxFile.contains(".ODT") || docxFile.contains(".ODS")) {
                this.logger.log("This is opendocument ! ", Level.INFO);
                if (isUnix){
                    cmd = "libreoffice --headless --convert-to pdf --outdir \""+this.userLocalDirTmp.substring(0,this.userLocalDirTmp.length()-1)+"\" \""+docxFile+"\"";
                }else{
                    String convertProgram;
                    convertProgram = this.fM.findPathProgramInRegistry("soffice.exe");
                    cmd = convertProgram+" -env:UserInstallation=$SYSUSERCONFIG --headless --convert-to pdf --outdir \""+this.userLocalDirTmp.substring(0,this.userLocalDirTmp.length()-1)+"\" \""+docxFile+"\" \r\n";
                }
                
            } else if (docxFile.contains(".doc") || docxFile.contains(".docx") || docxFile.contains(".DOC") || docxFile.contains(".DOCX")) {
                if (this.useExeConvert.equals("false")) {
                        if (isUnix){
                            cmd = "libreoffice --headless --convert-to pdf --outdir \""+this.userLocalDirTmp.substring(0,this.userLocalDirTmp.length()-1)+"\" \""+docxFile+"\"";
                        }else{
                            cmd = "cmd /C cscript \""+this.vbsPath+"\" \""+docxFile+"\" /nologo \r\n";
                        }
                } else {

                        StringBuffer buffer = new StringBuffer(docxFile);
                        buffer.replace(buffer.lastIndexOf("."),	buffer.length(), ".pdf");
                        String pdfOut = buffer.toString();

                        cmd = "cmd /C \""+this.userLocalDirTmp+"Word2Pdf.exe\" \""+docxFile+"\" \""+pdfOut+"\" \r\n";
                }
            } else {
                conversion = false;
            }
            
            if (conversion) {
                this.logger.log("EXEC PATH : " +cmd, Level.INFO);
                FileManager fM = new FileManager();

                Process proc_vbs;
                if (isUnix){
                    //cmd = "cscript \""+this.vbsPath+"\" \""+docxFile+"\" /nologo \r\n";
                    final Writer outBat;
                    outBat = new OutputStreamWriter(new FileOutputStream(this.appPath_convert), "CP850");
                    this.logger.log("--- cmd sh  --- "+cmd, Level.INFO);
                    outBat.write(cmd);
                    //outBat.write("exit \r\n");
                    outBat.close();

                    File myFileBat = new File(this.appPath_convert);
                    myFileBat.setReadable(true, false);
                    myFileBat.setWritable(true, false);
                    myFileBat.setExecutable(true, false);

                    /*String cmd2 = "start /WAIT /MIN "+this.appPath_convert+" \r\n";
                    final Writer outBat2 = new OutputStreamWriter(new FileOutputStream(this.appPath), "CP850");
                    outBat2.write(cmd2);
                    outBat2.write("exit \r\n");
                    outBat2.close();*/

                    /*File myFileBat2 = new File(this.appPath);
                    myFileBat2.setReadable(true, false);
                    myFileBat2.setWritable(true, false);
                    myFileBat2.setExecutable(true, false);*/

                    final String exec_vbs = "\""+this.appPath+"\"";
                    proc_vbs = fM.launchApp(this.appPath_convert);
                } else {
                    proc_vbs = fM.launchApp(cmd);
                }
                proc_vbs.waitFor();
            }

        } catch (Throwable e) {
            this.logger.log("Erreur ! : "+e, Level.SEVERE);
            e.printStackTrace();
        }
    }
    
    /**
     * Retrieve the xml message from Maarch and parse it
     * @param flux_xml xml content message
     */
    public void parse_xml(InputStream flux_xml) throws SAXException, IOException, ParserConfigurationException
    {
        this.logger.log("----------BEGIN PARSE XML----------", Level.INFO);
        DocumentBuilder builder = DocumentBuilderFactory.newInstance().newDocumentBuilder();

        try {
            Document doc = builder.parse(flux_xml);
            this.messageResult.clear();
            NodeList level_one_list = doc.getChildNodes();
            for (Integer i=0; i < level_one_list.getLength(); i++) {
                NodeList level_two_list = level_one_list.item(i).getChildNodes();
                if ("SUCCESS".equals(level_one_list.item(i).getNodeName())) {
                    for(Integer j=0; j < level_one_list.item(i).getChildNodes().getLength(); j++ ) {
                        this.messageResult.put(level_two_list.item(j).getNodeName(),level_two_list.item(j).getTextContent());
                    }
                    this.messageStatus = "SUCCESS";
                } else if ("ERROR".equals(level_one_list.item(i).getNodeName()) ) {
                    for(Integer j=0; j < level_one_list.item(i).getChildNodes().getLength(); j++ ) {
                        this.messageResult.put(level_two_list.item(j).getNodeName(),level_two_list.item(j).getTextContent());
                    }
                    this.messageStatus = "ERROR";
                }
            }
        } catch (SAXException | IOException e) {
            
            this.logger.log("ERREUR : Le document n'a pas pu être transféré du coté client. Assurez-vous que le modèle n'est pas corrompu et que la zone de stockage des templates soit correct.", Level.SEVERE);
            this.messageStatus = "ERROR";
            this.messageResult.put("ERROR","ERREUR : Le document n'a pas pu être transféré du coté client. Assurez-vous que le modèle n'est pas corrompu et que la zone de stockage des templates soit correct.");
            JOptionPane.showMessageDialog(null,"ERREUR ! L'édition de votre document a échoué. Assurez-vous que le modèle n'est pas corrompu et que la zone de stockage des modèles soit correct.");
        }
        this.logger.log("----------END PARSE XML----------", Level.INFO);
    }
    
    /**
     * Manage the return of program execution
     * @param result result of the program execution
     */
    public void processReturn(Hashtable result) {
        Iterator itValue = result.values().iterator(); 
        Iterator itKey = result.keySet().iterator();
        while(itValue.hasNext()) {
            String value = (String)itValue.next();
            String key = (String)itKey.next();
            this.logger.log(key + " : " + value, Level.INFO);
            if ("STATUS".equals(key)) {
                this.status = value;
            }
            if ("OBJECT_TYPE".equals(key)) {
                this.objectType = value;
            }
            if ("OBJECT_TABLE".equals(key)) {
                this.objectTable = value;
            }
            if ("OBJECT_ID".equals(key)) {
                this.objectId = value;
            }
            if ("UNIQUE_ID".equals(key)) {
                this.uniqueId = value;
            }
            if ("COOKIE".equals(key)) {
                this.cookie = value;
            }
            if ("CLIENTSIDECOOKIES".equals(key)) {
                this.clientSideCookies = value;
            }
            if ("APP_PATH".equals(key)) {
                //this.appPath = value;
            }
            if ("FILE_CONTENT".equals(key)) {
                this.fileContent = value;
            }
            if ("FILE_CONTENT_VBS".equals(key)) {
                this.fileContentVbs = value;
            }
            if ("VBS_PATH".equals(key)) {
                this.vbsPath = value;
            }
            if ("FILE_CONTENT_EXE".equals(key)) {
                this.fileContentExe = value;
            }
            if ("USE_EXE_CONVERT".equals(key)) {
                this.useExeConvert = value;
            }
            if ("FILE_EXTENSION".equals(key)) {
                this.fileExtension = value;
            }
            if ("ERROR".equals(key)) {
                this.error = value;
            }
            if ("END_MESSAGE".equals(key)) {
                this.endMessage = value;
            }
        }
        //send message error to Maarch if necessary
        if (!this.error.isEmpty()) {
            //this.sendJsMessage(this.error);
            this.destroy();
            this.stop();
            System.exit(0);
        }
    }
    
    /**
     * Main function of the class
     * enables you to edit document with the user favorit editor
     * @return nothing
     * @throws java.lang.Exception
     */
    public String editObject() throws Exception, InterruptedException, JSException {
        
        System.out.println("----------BEGIN EDIT OBJECT---------- LGI by DIS 27/09/2016");
        System.out.println("----------BEGIN LOCAL DIR TMP IF NOT EXISTS----------");
        String os = System.getProperty("os.name").toLowerCase();
        boolean isUnix = os.contains("nix") || os.contains("nux");
        boolean isWindows = os.contains("win");
        boolean isMac = os.contains("mac");
        this.userLocalDirTmp = System.getProperty("user.home");
        
        this.fM = new FileManager();

        if (isWindows) {
            System.out.println("This is Windows");
            this.userLocalDirTmp = this.userLocalDirTmp + "\\maarchTmp\\";
            this.appPath = this.userLocalDirTmp + "start.bat";
            this.appPath_convert = this.userLocalDirTmp + "conversion.bat";
            this.os = "win";
        } else if (isMac) {
            System.out.println("This is Mac");
            this.userLocalDirTmp = this.userLocalDirTmp + "/maarchTmp/";
            this.appPath = this.userLocalDirTmp + "start.sh";
            this.appPath_convert = this.userLocalDirTmp + "conversion.sh";
            this.os = "mac";
        } else if (isUnix) {
            System.out.println("This is Unix or Linux");
            this.userLocalDirTmp = this.userLocalDirTmp + "/maarchTmp/";
            this.appPath = this.userLocalDirTmp + "start.sh";
            this.appPath_convert = this.userLocalDirTmp + "conversion.sh";
            this.os = "linux";
        } else {
            System.out.println("Your OS is not supported!!");
        }
        
        /*String info = this.fM.createUserLocalDirTmp(this.userLocalDirTmp);
        
        if(info == "ERROR"){
            this.logger.log("ERREUR : Permissions insuffisante sur votre répertoire temporaire maarch", Level.SEVERE);   
            this.messageStatus = "ERROR";
            this.messageResult.clear();
            this.messageResult.put("ERROR","ERREUR : Permissions insuffisante sur votre répertoire temporaire maarch");
        }*/
        
        System.out.println("APP PATH: " + this.appPath);
        System.out.println("----------BEGIN LOCAL DIR TMP IF NOT EXISTS----------");
        
        String info = this.fM.createUserLocalDirTmp(this.userLocalDirTmp, this.os);
        
        if(info == "ERROR"){
            this.logger.log("ERREUR : Permissions insuffisante sur votre répertoire temporaire maarch", Level.SEVERE);
            this.messageStatus = "ERROR";
            this.messageResult.clear();
            this.messageResult.put("ERROR","ERREUR : Permissions insuffisante sur votre répertoire temporaire maarch");
            JOptionPane.showMessageDialog(null,"ERREUR ! Permissions insuffisante sur votre répertoire temporaire maarch.");
            this.processReturn(this.messageResult);
        }
        
        System.out.println("Create the logger");
        this.logger = new MyLogger(this.userLocalDirTmp);
        
        this.logger.log("Delete thefile if exists", Level.INFO);
        FileManager.deleteFilesOnDir(this.userLocalDirTmp, "thefile");
        
        this.logger.log("----------BEGIN OPEN REQUEST----------", Level.INFO);
        String urlToSend = this.url + "?action=editObject&objectType=" + this.objectType
                        + "&objectTable=" + this.objectTable + "&objectId=" + this.objectId
                        + "&uniqueId=" + this.uniqueId;
        sendHttpRequest(urlToSend, "none", false);
        this.logger.log("MESSAGE STATUS : " + this.messageStatus, Level.INFO);
        this.logger.log("MESSAGE RESULT : ", Level.INFO);
        this.processReturn(this.messageResult);
        this.logger.log("----------END OPEN REQUEST----------", Level.INFO);
        
        Integer randomNum;
        Integer minimum = 1;
        Integer maximum = 1000;
        
        randomNum = minimum + (int)(Math.random()*maximum); 
        this.fileToEdit = "thefile_" + randomNum + "." + this.fileExtension;
        
        this.logger.log("----------BEGIN CREATE THE BAT TO LAUNCH IF NECESSARY----------", Level.INFO);
        this.logger.log("create the file : "  + this.appPath, Level.INFO);
        this.fM.createBatFile(
            this.appPath, 
            this.userLocalDirTmp, 
            this.fileToEdit, 
            this.os
        );
        this.logger.log("----------END CREATE THE BAT TO LAUNCH IF NECESSARY----------", Level.INFO);
        
        if ("ok".equals(this.status)) {
            this.logger.log("RESPONSE OK", Level.INFO);
            
            this.logger.log("CREATE FILE IN LOCAL PATH", Level.INFO);
            if (this.useExeConvert.equals("false")){
            	this.logger.log("---------- VBS FILE ----------", Level.INFO);
            	this.logger.log(" Path = "+this.vbsPath, Level.INFO);
            	if (this.vbsPath.equals("")) this.vbsPath = this.userLocalDirTmp + "DOC2PDF_VBS.vbs";
            	boolean isVbsExists = this.fM.isPsExecFileExists(this.vbsPath);
            	if (!isVbsExists) fM.createFile(this.fileContentVbs, this.vbsPath);
            }
            else {
            	boolean isConvExecExists = this.fM.isPsExecFileExists(this.userLocalDirTmp + "Word2Pdf.exe");
            	if (!isConvExecExists) fM.createFile(this.fileContentExe, this.userLocalDirTmp + "Word2Pdf.exe");
            }
            
            this.logger.log("----------BEGIN EXECUTION OF THE EDITOR----------", Level.INFO);
            this.logger.log("CREATE FILE IN LOCAL PATH", Level.INFO);
            this.fM.createFile(this.fileContent, this.userLocalDirTmp + this.fileToEdit);

            Thread theThread;
            theThread = new Thread(new ProcessLoop(this));

            //theThread.logger = this.logger;

            theThread.start();
            
            String actualContent;
            this.fileContentTosend = "";
            do {
                theThread.sleep(3000);
                File fileTotest = new File(this.userLocalDirTmp + this.fileToEdit);
                if(fileTotest.canRead()) {
                    actualContent = FileManager.encodeFile(this.userLocalDirTmp + this.fileToEdit);
                    if (!this.fileContentTosend.equals(actualContent)) {
                        this.fileContentTosend = actualContent;
                        this.logger.log("----------[SECURITY BACKUP] BEGIN SEND OF THE OBJECT----------", Level.INFO);
                        String urlToSave = this.url + "?action=saveObject&objectType=" + this.objectType 
                                    + "&objectTable=" + this.objectTable + "&objectId=" + this.objectId
                                    + "&uniqueId=" + this.uniqueId + "&step=backup&userMaarch=" + this.userMaarch;
                        this.logger.log("[SECURITY BACKUP] URL TO SAVE : " + urlToSave, Level.INFO);
                        sendHttpRequest(urlToSave, this.fileContentTosend,false);
                        this.logger.log("[SECURITY BACKUP] MESSAGE STATUS : " + this.messageStatus, Level.INFO);
                    }
                } else {
                    this.logger.log(this.userLocalDirTmp + this.fileToEdit + " FILE NOT READABLE !!!!!!", Level.INFO);
                }
            }
            while (theThread.isAlive());
            
            theThread.interrupt();
            
            this.logger.log("----------END EXECUTION OF THE EDITOR----------", Level.INFO);
            
            this.logger.log("----------BEGIN RETRIEVE CONTENT OF THE OBJECT----------", Level.INFO);

            this.fileContentTosend = FileManager.encodeFile(this.userLocalDirTmp + this.fileToEdit);
            
            this.logger.log("----------END RETRIEVE CONTENT OF THE OBJECT----------", Level.INFO);
            
            this.logger.log("----------CONVERSION PDF----------", Level.INFO);
            createPDF(this.userLocalDirTmp + this.fileToEdit, this.userLocalDirTmp, isUnix);
            
            String pdfFile = this.userLocalDirTmp + "thefile_" + randomNum + ".pdf";
            
            this.logger.log("----------BEGIN RETRIEVE CONTENT OF THE OBJECT----------", Level.INFO);
            if (this.fM.isPsExecFileExists(pdfFile)){           
          	  this.pdfContentTosend = FileManager.encodeFile(pdfFile);
            }
            else {
            	this.pdfContentTosend = "null";
            	this.logger.log("ERREUR DE CONVERSION PDF !", Level.WARNING);
                JOptionPane.showMessageDialog(null,"Attention ! La conversion PDF a échoué mais le document a bien été transféré.");
            }
            
            this.logger.log("----------END RETRIEVE CONTENT OF THE OBJECT----------", Level.INFO);
            
            this.logger.log("---------- FIN CONVERSION PDF----------", Level.INFO);
            
            String urlToSave = this.url + "?action=saveObject&objectType=" + this.objectType 
                            + "&objectTable=" + this.objectTable + "&objectId=" + this.objectId
                            + "&uniqueId=" + this.uniqueId + "&step=end&userMaarch=" + this.userMaarch;
            this.logger.log("----------BEGIN SEND OF THE OBJECT----------", Level.INFO);
            this.logger.log("URL TO SAVE : " + urlToSave, Level.INFO);
            sendHttpRequest(urlToSave, this.fileContentTosend, true);
            this.logger.log("MESSAGE STATUS : " + this.messageStatus, Level.INFO);
            this.logger.log("LAST MESSAGE RESULT : ", Level.INFO);
            this.processReturn(this.messageResult);
            
            if(this.pdfContentTosend == "null"){
                this.endMessage = this.endMessage + " mais la conversion pdf n'a pas fonctionné (le document ne pourra pas être signé)";
            }
            //send message to Maarch at the end
            if (!this.endMessage.isEmpty()) {
                //this.sendJsMessage(this.endMessage);
            }
            //this.sendJsEnd();
            this.logger.log("----------END SEND OF THE OBJECT----------", Level.INFO);
        } else {
            this.logger.log("RESPONSE KO", Level.WARNING);
        }
        this.logger.log("----------END EDIT OBJECT----------", Level.INFO);
        return "ok";
    }
    
    /**
     * Class to manage the execution of an external program
     */
    public class ProcessLoop extends Thread {
        public DisCM disCM;
        
        public ProcessLoop(DisCM DisCM){
            this.disCM = DisCM;
        }

        public void run() {
            try {
            	disCM.launchProcess();
            } catch (PrivilegedActionException ex) {
                Logger.getLogger(DisCM.class.getName()).log(Level.SEVERE, null, ex);
            } catch (InterruptedException ex) {
                Logger.getLogger(DisCM.class.getName()).log(Level.SEVERE, null, ex);
            } catch (IllegalArgumentException ex) {
                Logger.getLogger(DisCM.class.getName()).log(Level.SEVERE, null, ex);
            } catch (IllegalAccessException ex) {
                Logger.getLogger(DisCM.class.getName()).log(Level.SEVERE, null, ex);
            } catch (InvocationTargetException ex) {
                Logger.getLogger(DisCM.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
    
    /**
     * Launch the external program and wait his execution end
     * @return boolean
     */
    public boolean launchProcess() throws PrivilegedActionException, InterruptedException, IllegalArgumentException, IllegalAccessException, InvocationTargetException
    {
        Process proc;

        this.logger.log("LAUNCH THE EDITOR !", Level.INFO);
        if ("linux".equals(this.os) || "mac".equals(this.os)) {
            proc = this.fM.launchApp(this.appPath);
        } else {
            this.logger.log("FILE TO EDIT : " + this.userLocalDirTmp + this.fileToEdit, Level.INFO);
            
            String programName;
            programName = this.fM.findGoodProgramWithExt(this.fileExtension);
            this.logger.log("PROGRAM NAME TO EDIT : " + programName, Level.INFO);
            String pathProgram;
            pathProgram = this.fM.findPathProgramInRegistry(programName);
            this.logger.log("PROGRAM PATH TO EDIT : " + pathProgram, Level.INFO);
            String options;
            options = this.fM.findGoodOptionsToEdit(this.fileExtension);
            this.logger.log("OPTION PROGRAM TO EDIT " + options, Level.INFO);
            String pathCommand;
            pathCommand = pathProgram + " " + options + "\""+this.userLocalDirTmp + this.fileToEdit+"\"";
            this.logger.log("PATH COMMAND TO EDIT " + pathCommand, Level.INFO);
            proc = this.fM.launchApp(pathCommand);
        }
        
        this.logger.log("WAIT END OF THE PROCESS", Level.INFO);
        proc.waitFor();
        this.logger.log("END OF THE PROCESS", Level.INFO);
        
        return true;
    }
    
    /**
     * Send a string message to Maarch with javascript
     * @param message
     */
    public void sendJsMessage(String message) throws JSException
    {
        JSObject jso;
        jso = JSObject.getWindow((Applet)this);
        this.logger.log("----------JS CALL sendAppletMsg TO MAARCH----------", Level.INFO);
        //String theMessage;
        //theMessage = String.valueOf(message);
        String[] theMessage = {String.valueOf(message), message};
        this.logger.log("Envoi du message à MAARCH", Level.INFO);
        jso.call("sendAppletMsg", (Object[]) theMessage);
        this.logger.log("----------END OF JS CALL----------", Level.INFO);
    }
    
    /**
     * Warns Maarch of the end of the execution of the applet
     */
    public void sendJsEnd() throws InterruptedException, JSException
    {
        JSObject jso;
        jso = JSObject.getWindow((Applet)this);
        this.logger.log("----------JS CALL endOfApplet TO MAARCH----------", Level.INFO);
        String[] theMessage = {String.valueOf(this.objectType), this.endMessage};
        jso.call("endOfApplet", (Object[]) theMessage);
        this.logger.log("----------END OF JS CALL----------", Level.INFO);
    }
    
    /**
     * Send an http request to Maarch
     * @param theUrl url to contact Maarch
     * @param postRequest the request
     * @param endProcess end request
     */
    public void sendHttpRequest(String theUrl, String postRequest, boolean endProcess) throws Exception {
        System.out.println("URL request : " + theUrl);
        URL UrlOpenRequest = new URL(theUrl);
        try {
            HttpURLConnection HttpOpenRequest = (HttpURLConnection) UrlOpenRequest.openConnection();
            HttpOpenRequest.setDoOutput(true);
            HttpOpenRequest.setRequestMethod("POST");
            StringBuffer sb = new StringBuffer(this.cookie);
            if(this.clientSideCookies != null && this.clientSideCookies.length() > 0) {
                sb.append(';');
                //sb.append(this.clientSideCookies.replaceAll(";", ","));
                sb.append(this.clientSideCookies);
            }
            this.logger.log("COOKIES SENDED : " + sb.toString(), Level.INFO);
            HttpOpenRequest.setRequestProperty("Cookie", sb.toString());
            if (!"none".equals(postRequest)) {
                OutputStreamWriter writer = new OutputStreamWriter(HttpOpenRequest.getOutputStream());
                if (endProcess){
                    if (!this.pdfContentTosend.equals("null"))
                            writer.write("fileContent=" + this.fileContentTosend + "&fileExtension=" + this.fileExtension+ "&pdfContent=" + this.pdfContentTosend);
                    else writer.write("fileContent=" + this.fileContentTosend + "&fileExtension=" + this.fileExtension);
                }
                else writer.write("fileContent=" + this.fileContentTosend + "&fileExtension=" + this.fileExtension);
                writer.flush();
            } else {
                OutputStreamWriter writer = new OutputStreamWriter(HttpOpenRequest.getOutputStream());
                writer.write("foo=bar");
                writer.flush();
            }
            this.parse_xml(HttpOpenRequest.getInputStream());
            HttpOpenRequest.disconnect();
        } catch (Exception ex) {
            this.logger.log("erreur: "+ex, Level.SEVERE);
            JOptionPane.showMessageDialog(null,"ERREUR ! La connexion au serveur a été interrompue, le document édité n'a pas été sauvegardé !");
        }
        
    }
}
