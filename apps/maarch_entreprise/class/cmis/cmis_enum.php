<?php
class DecimalPrecision{
	public static $_32  =  32 ;
	public static $_64  =  64 ;
}


class ContentStreamAllowed{
	public static $NotAllowed  =  "notallowed" ;
	public static $Allowed  =  "allowed" ;
	public static $Required  =  "required" ;
}


class Cardinality{
	public static $Single  =  "single" ;
	public static $Multi  =  "multi" ;
}


class Updatability{
	public static $ReadOnly  =  "readonly" ;
	public static $ReadWrite  =  "readwrite" ;
	public static $WhenCheckedOut  =  "whencheckedout" ;
	public static $OnCreate  =  "oncreate" ;
}


class DateTimeResolution{
	public static $Year  =  "year" ;
	public static $Date  =  "date" ;
	public static $Time  =  "time" ;
}


class PropertyType{
	public static $Boolean = "boolean" ;
	public static $Id = "id" ;
	public static $Integer = "integer" ;
	public static $DateTime = "datetime" ;
	public static $Decimal = "decimal" ;
	public static $Html = "html" ;
	public static $String = "string" ;
	public static $Uri = "uri" ;
}


class BaseObjectTypeIds{
	public static $CmisDocument = "cmis:document" ;
	public static $CmisFolder = "cmis:folder" ;
	public static $CmisRelationship = "cmis:relationship" ;
	public static $CmisPolicy = "cmis:policy" ;
}


class CapabilityQuery{
	public static $None = "none" ;
	public static $MetadataOnly = "metadataonly" ;
	public static $FulltextOnly = "fulltextonly" ;
	public static $BothSeparate = "bothseparate" ;
	public static $BothCombined = "bothcombined" ;
}


class CapabilityJoin{
	public static $None = "none" ;
	public static $InnerOnly = "inneronly" ;
	public static $InnerAndOut = "innerandouter" ;
}


class CapabilityContentStreamUpdates{
	public static $Anytime = "anytime" ;
	public static $PwcOnly = "pwconly" ;
	public static $None = "none" ;
}


class VersioningState{
	public static $None = "none" ;
	public static $CheckedOut = "checkedout" ;
	public static $Minor = "minor" ;
	public static $Major = "major" ;
}


class UnfileObject{
	public static $Unfile = "unfile" ;
	public static $DeleteSingleFiled = "deletesinglefiled" ;
	public static $Delete = "delete" ;
}


class RelationshipDirection{
	public static $Source = "source" ;
	public static $Target = "target" ;
	public static $Either = "either" ;
}


class IncludeRelationships{
	public static $None   = "none"   ;
	public static $Source = "source" ;
	public static $Target = "target" ;
	public static $Both   = "both"   ;
}


class PropertiesBase{
	public static $CmisName = "cmis:name" ;
	public static $CmisObjectId = "cmis:objectId" ;
	public static $CmisObjectTypeId = "cmis:objectTypeId" ;
	public static $CmisBaseTypeId = "cmis:baseTypeId" ;
	public static $CmisCreatedBy = "cmis:createdBy" ;
	public static $CmisCreationDate = "cmis:creationDate" ;
	public static $CmisLastModifiedBy = "cmis:lastModifiedBy" ;
	public static $CmisLastModificationDate = "cmis:lastModificationDate" ;
	public static $CmisChangeToken = "cmis:changeToken";
}


class PropertiesDocument{
	public static $CmisIsImmutable = "cmis:isImmutable" ;
	public static $CmisIsLatestVersion = "cmis:isLatestVersion" ;
	public static $CmisIsMajorVersion = "cmis:isMajorVersion" ;
	public static $CmisIsLatestMajorVersion = "cmis:isLatestMajorVersion" ;
	public static $CmisVersionLabel = "cmis:versionLabel" ;
	public static $CmisVersionSeriesId = "cmis:versionSeriesId" ;
	public static $CmisIsVersionSeriesCheckOut = "cmis:isVersionSeriesCheckedOut" ;
	public static $CmisVersionSeriesCheckedOutBy = "cmis:versionSeriesCheckedOutBy" ;
	public static $CmisVersionSeriesCheckedOutId = "cmis:versionSeriesCheckedOutId" ;
	public static $CmisCheckinComment = "cmis:checkinComment" ;
	public static $CmisContentStreamLength = "cmis:contentStreamLength" ;
	public static $CmisContentStreamMimeType = "cmis:contentStreamMimeType" ;
	public static $CmisContentStreamFileName = "cmis:contentStreamFileName" ;
	public static $CmisContentStreamId = "cmis:contentStreamId";
}


class PropertiesFolder{
	public static $CmisParentId = "cmis:parentId" ;
	public static $CmisAllowedChildObjectTypeIds = "cmis:allowedChildObjectTypeIds" ;
	public static $CmisPath = "cmis:path";
}


class PropertiesRelationship{
	public static $CmisSourceId = "cmis:sourceId" ;
	public static $CmisTargetId = "cmis:targetId" ;
}


class PropertiesPolicy{
	public static $CmisPolicyText = "cmis:policyText";
}


class TypeOfChanges{
	public static $Created= "created"  ;
	public static $Updated = "updated"  ;
	public static $Deleted = "deleted"  ;
	public static $Security = "security"  ;
}


class CapabilityChanges{ 
	public static $None = "none"  ;
	public static $ObjectIdsOnly = "objectidsonly"  ;
	public static $Properties = "properties"  ;
	public static $All = "all"  ;
}


class ACLPropagation{ 
	public static $RepositoryDetermined = "repositorydetermined"  ;
	public static $ObjectOnly = "objectonly"  ;
	public static $Propagate = "propagate"  ;
}

class CapabilityACL{ 
	public static $None = "none"  ;
	public static $Discover = "discover"  ;
	public static $Manage = "manage"  ;
}

class BasicPermissions{ 
	public static $CmisRead = "cmis:read"  ;
	public static $CmisWrite = "cmis:write"  ;
	public static $CmisAll = "cmis:all"  ;
}




class AllowableActionsKey{ 
	public static $CanGetDescendentsFolder = "canGetDescendents.Folder"  ;
	public static $CanGetChildrenFolder = "canGetChildren.Folder"  ;
	public static $CanGetParentsFolder = "canGetParents.Folder"  ;
	public static $CanGetFolderParentObject = "canGetFolderParent.Object"  ;
	public static $CanCreateDocumentFolder = "canCreateDocument.Folder"  ;
	public static $CanCreateFolderFolder = "canCreateFolder.Folder"  ;
	public static $CanCreateRelationshipSource = "canCreateRelationship.Source"  ;
	public static $CanCreateRelationshipTarget = "canCreateRelationship.Target"  ;
	public static $CanGetPropertiesObject = "canGetProperties.Object"  ;
	public static $CanViewContentObject = "canViewContent.Object"  ;
	public static $CanUpdatePropertiObject = "canUpdateProperties.Object"  ;
	public static $CanMoveObject = "canMove.Object"  ;
	public static $CanMoveTarget = "canMove.Target"  ;
	public static $CanMoveSource = "canMove.Source"  ;
	public static $CanDeleteObject = "canDelete.Object"  ;
	public static $CanDeleteTreeFolder = "canDeleteTree.Folder"  ;
	public static $CanSetContentdocument = "canSetContent.Document"  ;
	public static $CanDeleteContentDocument = "canDeleteContent.Document"  ;
	public static $CanAddToFolderObject = "canAddToFolder.Object"  ;
	public static $CanAddToFolderFolder = "canAddToFolder.Folder"  ;
	public static $CanRemoveFromFolderObject = "canRemoveFromFolder.Object"  ;
	public static $CanRemoveFromFolderFolder = "canRemoveFromFolder.Folder"  ;
	public static $CanCheckoutDocument = "canCheckout.Document"  ;
	public static $CanCancelCheckoutDocument = "canCancelCheckout.Document"  ;
	public static $CanCheckinDocument = "canCheckin.Document"  ;
	public static $CanGetAllVersionsVersionSeries = "canGetAllVersions.VersionSeries"  ;
	public static $CanGetObjectRelationshipsObject = "canGetObjectRelationships.Object"  ;
	public static $CanAddPolicyObject = "canAddPolicy.Object"  ;
	public static $CanAddPolicyPolicy = "canAddPolicy.Policy"  ;
	public static $CanRemovePolicyObject = "canRemovePolicy.Object"  ;
	public static $CanRemovePolicyPolicy = "canRemovePolicy.Policy"  ;
	public static $CanGetAppliedPoliciesObject = "canGetAppliedPolicies.Object"  ;
	public static $CanGetACLObject = "canGetACL.Object"  ;
	public static $CanApplyACLObject = "canApplyACL.Object"  ;
}


class SupportedPermissions{ 
	public static $Basic = "basic"  ;
	public static $Repository = "repository"  ;
	public static $Both = "both"  ;
}

class CapabilityRendition{ 
	public static $None = "none"  ;
	public static $Read = "read"  ;
}


class RenditionKind{ 
	public static $CmisThumbnail = "cmis:thumbnail"  ;
}









