<?php 

class Rendition{
  /*
   * ID
   * Identifies the rendition stream
  */
  private $streamId;

  /*
   * String
   * The MIME type of the rendition stream.
  */
  private $mimeType;
  
  /*
   * Integer (optional)
   * The length of the rendition stream in bytes.
  */
  private $length;

  /*
   *  String (optional)
   * Human readable information about the rendition.
  */
  private $title;

  /*
   * String
   * A categorization String associated with the rendition.
  */
  private $kind;
  
  
  /*
   * Integer (optional)
   * Typically used for 'image' renditions (expressed as pixels). SHOULD be present if kind = cmis:thumbnail
  */
  private $height;
  
  
  /*
   * Integer (optional)
   * Typically used for 'image' renditions (expressed as pixels). SHOULD be present if kind = cmis:thumbnail
   */
  private $width;
  
  
  /*
   *ID (optional)
   */
  private $renditionDocumentId;
  
  




}


