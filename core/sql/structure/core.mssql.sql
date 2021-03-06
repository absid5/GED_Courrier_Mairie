SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usergroups]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[usergroups](
    [group_id] [varchar](32) NOT NULL,
    [group_desc] [varchar](255) NULL DEFAULT (NULL),
    [administrator] [char](1) NOT NULL DEFAULT ('N'),
    [custom_right1] [char](1) NOT NULL DEFAULT ('N'),
    [custom_right2] [char](1) NOT NULL DEFAULT ('N'),
    [custom_right3] [char](1) NOT NULL DEFAULT ('N'),
    [custom_right4] [char](1) NOT NULL DEFAULT ('N'),
    [enabled] [char](1) NOT NULL DEFAULT ('Y'),
PRIMARY KEY CLUSTERED
(
    [group_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[res_x]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[res_x](
    [res_id] [int] IDENTITY(1,1) NOT NULL,
    [title] [varchar](255) NULL CONSTRAINT [DF__res_x__title__4830B400]  DEFAULT (NULL),
    [subject] [text] NULL,
    [description] [text] NULL,
    [publisher] [varchar](255) NULL CONSTRAINT [DF__res_x__publisher__4924D839]  DEFAULT (NULL),
    [contributor] [varchar](255) NULL CONSTRAINT [DF__res_x__contribut__4A18FC72]  DEFAULT (NULL),
    [type_id] [int] NOT NULL,
    [format] [varchar](50) NOT NULL,
    [typist] [varchar](50) NOT NULL,
    [creation_date] [datetime] NOT NULL,
    [author] [varchar](255) NULL CONSTRAINT [DF__res_x__author__4B0D20AB]  DEFAULT (NULL),
    [author_name] [text] NULL,
    [identifier] [varchar](255) NULL CONSTRAINT [DF__res_x__identifie__4C0144E4]  DEFAULT (NULL),
    [source] [varchar](255) NULL CONSTRAINT [DF__res_x__source__4CF5691D]  DEFAULT (NULL),
    [doc_language] [varchar](50) NULL CONSTRAINT [DF__res_x__doc_langu__4DE98D56]  DEFAULT (NULL),
    [relation] [int] NULL CONSTRAINT [DF__res_x__relation__4EDDB18F]  DEFAULT (NULL),
    [coverage] [varchar](255) NULL CONSTRAINT [DF__res_x__coverage__4FD1D5C8]  DEFAULT (NULL),
    [doc_date] [datetime] NULL CONSTRAINT [DF__res_x__doc_date__50C5FA01]  DEFAULT (NULL),
    [docserver_id] [varchar](32) NOT NULL,
    [folders_system_id] [int] NULL CONSTRAINT [DF__res_x__folders_s__51BA1E3A]  DEFAULT (NULL),
    [arbox_id] [varchar](32) NULL CONSTRAINT [DF__res_x__arbox_id__52AE4273]  DEFAULT (NULL),
    [path] [varchar](255) NULL CONSTRAINT [DF__res_x__path__53A266AC]  DEFAULT (NULL),
    [filename] [varchar](255) NULL CONSTRAINT [DF__res_x__filename__54968AE5]  DEFAULT (NULL),
    [offset_doc] [varchar](255) NULL CONSTRAINT [DF__res_x__offset_do__558AAF1E]  DEFAULT (NULL),
    [logical_adr] [varchar](255) NULL CONSTRAINT [DF__res_x__logical_a__567ED357]  DEFAULT (NULL),
    [fingerprint] [varchar](255) NULL CONSTRAINT [DF__res_x__fingerpri__5772F790]  DEFAULT (NULL),
    [filesize] [int] NULL CONSTRAINT [DF__res_x__filesize__58671BC9]  DEFAULT (NULL),
    [is_paper] [char](1) NULL CONSTRAINT [DF__res_x__is_paper__595B4002]  DEFAULT (NULL),
    [page_count] [int] NULL CONSTRAINT [DF__res_x__page_coun__5A4F643B]  DEFAULT (NULL),
    [scan_date] [datetime] NULL CONSTRAINT [DF__res_x__scan_date__5B438874]  DEFAULT (NULL),
    [scan_user] [varchar](50) NULL CONSTRAINT [DF__res_x__scan_user__5C37ACAD]  DEFAULT (NULL),
    [scan_location] [varchar](255) NULL CONSTRAINT [DF__res_x__scan_loca__5D2BD0E6]  DEFAULT (NULL),
    [scan_wkstation] [varchar](255) NULL CONSTRAINT [DF__res_x__scan_wkst__5E1FF51F]  DEFAULT (NULL),
    [scan_batch] [varchar](50) NULL CONSTRAINT [DF__res_x__scan_batc__5F141958]  DEFAULT (NULL),
    [burn_batch] [varchar](50) NULL CONSTRAINT [DF__res_x__burn_batc__60083D91]  DEFAULT (NULL),
    [scan_postmark] [varchar](50) NULL CONSTRAINT [DF__res_x__scan_post__60FC61CA]  DEFAULT (NULL),
    [envelop_id] [int] NULL CONSTRAINT [DF__res_x__envelop_i__61F08603]  DEFAULT (NULL),
    [status] [varchar](3) NULL CONSTRAINT [DF__res_x__status__62E4AA3C]  DEFAULT (NULL),
    [destination] [varchar](50) NULL CONSTRAINT [DF__res_x__destinati__63D8CE75]  DEFAULT (NULL),
    [approver] [varchar](50) NULL CONSTRAINT [DF__res_x__approver__64CCF2AE]  DEFAULT (NULL),
    [validation_date] [datetime] NULL CONSTRAINT [DF__res_x__validatio__65C116E7]  DEFAULT (NULL),
    [work_batch] [int] NULL CONSTRAINT [DF__res_x__work_batc__66B53B20]  DEFAULT (NULL),
    [origin] [varchar](50) NULL CONSTRAINT [DF__res_x__origin__67A95F59]  DEFAULT (NULL),
    [is_ingoing] [char](1) NULL CONSTRAINT [DF__res_x__is_ingoin__689D8392]  DEFAULT (NULL),
    [priority] [smallint] NULL CONSTRAINT [DF__res_x__priority__6991A7CB]  DEFAULT (NULL),
    [custom_t1] [text] NULL,
    [custom_n1] [int] NULL CONSTRAINT [DF__res_x__custom_n1__6A85CC04]  DEFAULT (NULL),
    [custom_f1] [decimal](10, 0) NULL CONSTRAINT [DF__res_x__custom_f1__6B79F03D]  DEFAULT (NULL),
    [custom_d1] [datetime] NULL CONSTRAINT [DF__res_x__custom_d1__6C6E1476]  DEFAULT (NULL),
    [custom_t2] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t2__6D6238AF]  DEFAULT (NULL),
    [custom_n2] [int] NULL CONSTRAINT [DF__res_x__custom_n2__6E565CE8]  DEFAULT (NULL),
    [custom_f2] [decimal](10, 0) NULL CONSTRAINT [DF__res_x__custom_f2__6F4A8121]  DEFAULT (NULL),
    [custom_d2] [datetime] NULL CONSTRAINT [DF__res_x__custom_d2__703EA55A]  DEFAULT (NULL),
    [custom_t3] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t3__7132C993]  DEFAULT (NULL),
    [custom_n3] [int] NULL CONSTRAINT [DF__res_x__custom_n3__7226EDCC]  DEFAULT (NULL),
    [custom_f3] [decimal](10, 0) NULL CONSTRAINT [DF__res_x__custom_f3__731B1205]  DEFAULT (NULL),
    [custom_d3] [datetime] NULL CONSTRAINT [DF__res_x__custom_d3__740F363E]  DEFAULT (NULL),
    [custom_t4] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t4__75035A77]  DEFAULT (NULL),
    [custom_n4] [int] NULL CONSTRAINT [DF__res_x__custom_n4__75F77EB0]  DEFAULT (NULL),
    [custom_f4] [decimal](10, 0) NULL CONSTRAINT [DF__res_x__custom_f4__76EBA2E9]  DEFAULT (NULL),
    [custom_d4] [datetime] NULL CONSTRAINT [DF__res_x__custom_d4__77DFC722]  DEFAULT (NULL),
    [custom_t5] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t5__78D3EB5B]  DEFAULT (NULL),
    [custom_n5] [int] NULL CONSTRAINT [DF__res_x__custom_n5__79C80F94]  DEFAULT (NULL),
    [custom_f5] [decimal](10, 0) NULL CONSTRAINT [DF__res_x__custom_f5__7ABC33CD]  DEFAULT (NULL),
    [custom_d5] [datetime] NULL CONSTRAINT [DF__res_x__custom_d5__7BB05806]  DEFAULT (NULL),
    [custom_t6] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t6__7CA47C3F]  DEFAULT (NULL),
    [custom_d6] [datetime] NULL CONSTRAINT [DF__res_x__custom_d6__7D98A078]  DEFAULT (NULL),
    [custom_t7] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t7__7E8CC4B1]  DEFAULT (NULL),
    [custom_d7] [datetime] NULL CONSTRAINT [DF__res_x__custom_d7__7F80E8EA]  DEFAULT (NULL),
    [custom_t8] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t8__00750D23]  DEFAULT (NULL),
    [custom_d8] [datetime] NULL CONSTRAINT [DF__res_x__custom_d8__0169315C]  DEFAULT (NULL),
    [custom_t9] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t9__025D5595]  DEFAULT (NULL),
    [custom_d9] [datetime] NULL CONSTRAINT [DF__res_x__custom_d9__035179CE]  DEFAULT (NULL),
    [custom_t10] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t1__04459E07]  DEFAULT (NULL),
    [custom_d10] [datetime] NULL CONSTRAINT [DF__res_x__custom_d1__0539C240]  DEFAULT (NULL),
    [custom_t11] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t1__062DE679]  DEFAULT (NULL),
    [custom_t12] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t1__07220AB2]  DEFAULT (NULL),
    [custom_t13] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t1__08162EEB]  DEFAULT (NULL),
    [custom_t14] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t1__090A5324]  DEFAULT (NULL),
    [custom_t15] [varchar](255) NULL CONSTRAINT [DF__res_x__custom_t1__09FE775D]  DEFAULT (NULL),
    [tablename] [varchar](32) NULL CONSTRAINT [DF__res_x__tablename__0AF29B96]  DEFAULT ('res_x'),
 CONSTRAINT [PK__res_x__473C8FC7] PRIMARY KEY CLUSTERED
(
    [res_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usergroups_services]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[usergroups_services](
    [group_id] [varchar](32) NOT NULL,
    [service_id] [varchar](32) NOT NULL,
PRIMARY KEY CLUSTERED
(
    [group_id] ASC,
    [service_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usergroup_content]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[usergroup_content](
    [user_id] [varchar](32) NOT NULL,
    [group_id] [varchar](32) NOT NULL,
    [primary_group] [char](1) NOT NULL,
    [role] [varchar](255) NULL DEFAULT (NULL),
PRIMARY KEY CLUSTERED
(
    [user_id] ASC,
    [group_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[folders_out]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[folders_out](
    [folder_out_id] [int] IDENTITY(1,1) NOT NULL,
    [folder_system_id] [int] NOT NULL,
    [last_name] [varchar](255) NOT NULL,
    [first_name] [varchar](255) NOT NULL,
    [last_name_folder_out] [varchar](255) NOT NULL,
    [first_name_folder_out] [varchar](255) NOT NULL,
    [put_out_pattern] [varchar](255) NOT NULL,
    [put_out_date] [datetime] NOT NULL,
    [return_date] [datetime] NOT NULL,
    [return_flag] [varchar](1) NOT NULL CONSTRAINT [DF__folders_o__retur__02FC7413]  DEFAULT ('N'),
 CONSTRAINT [PK__folders_out__02084FDA] PRIMARY KEY CLUSTERED
(
    [folder_out_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[users]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[users](
    [user_id] [varchar](32) NOT NULL,
    [password] [varchar](255) NULL DEFAULT (NULL),
    [firstname] [varchar](255) NULL DEFAULT (NULL),
    [lastname] [varchar](255) NULL DEFAULT (NULL),
    [phone] [varchar](15) NULL DEFAULT (NULL),
    [mail] [varchar](255) NULL DEFAULT (NULL),
    [department] [varchar](50) NULL DEFAULT (NULL),
    [custom_t1] [varchar](50) NULL DEFAULT (NULL),
    [custom_t2] [varchar](50) NULL DEFAULT (NULL),
    [custom_t3] [varchar](50) NULL DEFAULT (NULL),
    [cookie_key] [varchar](255) NULL DEFAULT (NULL),
    [cookie_date] [datetime] NULL DEFAULT (NULL),
    [enabled] [char](1) NOT NULL DEFAULT ('Y'),
    [change_password] [char](1) NOT NULL DEFAULT ('Y'),
    [delay_number] [int] NULL DEFAULT (NULL),
PRIMARY KEY CLUSTERED
(
    [user_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[foldertypes_doctypes]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[foldertypes_doctypes](
    [foldertype_id] [int] NOT NULL,
    [doctype_id] [int] NOT NULL,
PRIMARY KEY CLUSTERED
(
    [foldertype_id] ASC,
    [doctype_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[foldertypes_doctypes_level1]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[foldertypes_doctypes_level1](
    [foldertype_id] [int] NOT NULL,
    [doctypes_first_level_id] [int] NOT NULL,
PRIMARY KEY CLUSTERED
(
    [foldertype_id] ASC,
    [doctypes_first_level_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[groupbasket]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[groupbasket](
    [group_id] [varchar](32) NOT NULL,
    [basket_id] [varchar](32) NOT NULL,
    [sequence] [int] NOT NULL DEFAULT ('0'),
    [redirect_basketlist] [varchar](255) NULL DEFAULT (NULL),
    [redirect_grouplist] [varchar](255) NULL DEFAULT (NULL),
    [can_redirect] [char](1) NOT NULL DEFAULT ('Y'),
    [can_delete] [char](1) NOT NULL DEFAULT ('N'),
    [can_insert] [char](1) NOT NULL DEFAULT ('N'),
    [result_page] [varchar](255) NULL DEFAULT ('show_list1.php'),
PRIMARY KEY CLUSTERED
(
    [group_id] ASC,
    [basket_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[groupsecurity]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[groupsecurity](
    [group_id] [varchar](32) NOT NULL,
    [resgroup_id] [varchar](32) NOT NULL,
    [can_view] [char](1) NOT NULL,
    [can_add] [char](1) NOT NULL,
    [can_delete] [char](1) NOT NULL,
PRIMARY KEY CLUSTERED
(
    [group_id] ASC,
    [resgroup_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[autofoldering_node_item]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[autofoldering_node_item](
    [item_id] [bigint] IDENTITY(1,1) NOT NULL,
    [tree_id] [varchar](50) NOT NULL,
    [level] [int] NOT NULL,
    [key_value] [varchar](255) NOT NULL,
    [label_value] [varchar](255) NULL CONSTRAINT [DF__autofolde__label__00551192]  DEFAULT (NULL),
    [parent_item_id] [int] NULL CONSTRAINT [DF__autofolde__paren__014935CB]  DEFAULT (NULL),
    [status] [char](3) NOT NULL CONSTRAINT [DF__autofolde__statu__023D5A04]  DEFAULT ('OK'),
    [node_id] [varchar](255) NOT NULL
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[contracts]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[contracts](
    [contract_id] [bigint] IDENTITY(1,1) NOT NULL,
    [contract_label] [varchar](50) NOT NULL,
    [paid] [char](1) NOT NULL CONSTRAINT [DF__contracts__paid__0AD2A005]  DEFAULT ('Y'),
    [enabled] [char](1) NOT NULL CONSTRAINT [DF__contracts__enabl__0BC6C43E]  DEFAULT ('Y'),
 CONSTRAINT [PK__contracts__09DE7BCC] PRIMARY KEY CLUSTERED
(
    [contract_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[doctypes]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[doctypes](
    [coll_id] [varchar](32) NOT NULL CONSTRAINT [DF__doctypes__coll_i__1B0907CE]  DEFAULT (''),
    [type_id] [int] IDENTITY(1,1) NOT NULL,
    [description] [varchar](255) NOT NULL CONSTRAINT [DF__doctypes__descri__1BFD2C07]  DEFAULT (''),
    [enabled] [char](1) NOT NULL CONSTRAINT [DF__doctypes__enable__1CF15040]  DEFAULT ('Y'),
    [doctypes_first_level_id] [int] NULL CONSTRAINT [DF__doctypes__doctyp__1DE57479]  DEFAULT (NULL),
    [doctypes_second_level_id] [int] NULL CONSTRAINT [DF__doctypes__doctyp__1ED998B2]  DEFAULT (NULL),
    [primary_retention] [varchar](50) NULL CONSTRAINT [DF__doctypes__retent__1FCDBCEB]  DEFAULT (NULL),
    [secondary_retention] [varchar](50) NULL CONSTRAINT [DF__doctypes__retent__1FCDBCEB]  DEFAULT (NULL),
 CONSTRAINT [PK__doctypes__1A14E395] PRIMARY KEY CLUSTERED
(
    [type_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[parameters]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[parameters](
    [id] [varchar](50) NOT NULL,
    [param_value_string] [varchar](50) NULL DEFAULT (NULL),
    [param_value_int] [int] NULL DEFAULT (NULL),
    [param_value_date] [datetime] NULL DEFAULT (NULL),
PRIMARY KEY CLUSTERED
(
    [id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[resgroups]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[resgroups](
    [resgroup_id] [varchar](32) NOT NULL,
    [resgroup_desc] [varchar](255) NOT NULL,
    [created_by] [varchar](255) NOT NULL,
    [creation_date] [datetime] NOT NULL,
PRIMARY KEY CLUSTERED
(
    [resgroup_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[resgroup_content]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[resgroup_content](
    [coll_id] [varchar](8) NOT NULL,
    [res_id] [int] NOT NULL,
    [resgroup_id] [varchar](32) NOT NULL,
    [sequence] [int] NOT NULL,
PRIMARY KEY CLUSTERED
(
    [coll_id] ASC,
    [res_id] ASC,
    [resgroup_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[history]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[history](
    [id] [int] IDENTITY(1,1) NOT NULL,
    [table_name] [varchar](32) NULL CONSTRAINT [DF__history__table_n__3864608B]  DEFAULT (NULL),
    [record_id] [varchar](255) NULL CONSTRAINT [DF__history__record___395884C4]  DEFAULT (NULL),
    [event_type] [varchar](32) NOT NULL,
    [user_id] [varchar](50) NOT NULL,
    [event_date] [datetime] NOT NULL,
    [info] [text] NULL,
    [id_module] [varchar](50) NOT NULL CONSTRAINT [DF__history__id_modu__3A4CA8FD]  DEFAULT ('admin'),
 CONSTRAINT [PK__history__37703C52] PRIMARY KEY CLUSTERED
(
    [id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ext_docserver]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[ext_docserver](
    [doc_id] [varchar](255) NOT NULL,
    [path] [varchar](255) NOT NULL,
PRIMARY KEY CLUSTERED
(
    [doc_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[models]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[models](
    [id] [int] IDENTITY(1,1) NOT NULL,
    [label] [varchar](50) NULL CONSTRAINT [DF__models__label__3D2915A8]  DEFAULT (NULL),
    [creation_date] [datetime] NULL CONSTRAINT [DF__models__creation__3E1D39E1]  DEFAULT (NULL),
    [comment] [varchar](255) NULL CONSTRAINT [DF__models__comment__3F115E1A]  DEFAULT (NULL),
    [content] [text] NULL,
 CONSTRAINT [PK__models__3C34F16F] PRIMARY KEY CLUSTERED
(
    [id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[res_mail]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[res_mail](
    [res_id] [int] IDENTITY(1,1) NOT NULL,
    [title] [varchar](255) NULL CONSTRAINT [DF__res_mail__title__498EEC8D]  DEFAULT (NULL),
    [subject] [text] NULL,
    [description] [text] NULL,
    [publisher] [varchar](255) NULL CONSTRAINT [DF__res_mail__publis__4A8310C6]  DEFAULT (NULL),
    [contributor] [varchar](255) NULL CONSTRAINT [DF__res_mail__contri__4B7734FF]  DEFAULT (NULL),
    [type_id] [int] NOT NULL,
    [format] [varchar](50) NOT NULL,
    [typist] [varchar](50) NOT NULL,
    [creation_date] [datetime] NOT NULL,
    [author] [varchar](255) NULL CONSTRAINT [DF__res_mail__author__4C6B5938]  DEFAULT (NULL),
    [author_name] [text] NULL,
    [identifier] [varchar](255) NULL CONSTRAINT [DF__res_mail__identi__4D5F7D71]  DEFAULT (NULL),
    [source] [varchar](255) NULL CONSTRAINT [DF__res_mail__source__4E53A1AA]  DEFAULT (NULL),
    [doc_language] [varchar](50) NULL CONSTRAINT [DF__res_mail__doc_la__4F47C5E3]  DEFAULT (NULL),
    [relation] [int] NULL CONSTRAINT [DF__res_mail__relati__503BEA1C]  DEFAULT (NULL),
    [coverage] [varchar](255) NULL CONSTRAINT [DF__res_mail__covera__51300E55]  DEFAULT (NULL),
    [doc_date] [datetime] NULL CONSTRAINT [DF__res_mail__doc_da__5224328E]  DEFAULT (NULL),
    [rights] [varchar](20) NULL CONSTRAINT [DF__res_mail__rights__531856C7]  DEFAULT (NULL),
    [docserver_id] [varchar](32) NOT NULL,
    [folders_system_id] [int] NULL CONSTRAINT [DF__res_mail__folder__540C7B00]  DEFAULT (NULL),
    [arbox_id] [varchar](32) NULL CONSTRAINT [DF__res_mail__arbox___55009F39]  DEFAULT (NULL),
    [path] [varchar](255) NULL CONSTRAINT [DF__res_mail__path__55F4C372]  DEFAULT (NULL),
    [filename] [varchar](255) NULL CONSTRAINT [DF__res_mail__filena__56E8E7AB]  DEFAULT (NULL),
    [offset_doc] [varchar](255) NULL CONSTRAINT [DF__res_mail__offset__57DD0BE4]  DEFAULT (NULL),
    [logical_adr] [varchar](255) NULL CONSTRAINT [DF__res_mail__logica__58D1301D]  DEFAULT (NULL),
    [fingerprint] [varchar](255) NULL CONSTRAINT [DF__res_mail__finger__59C55456]  DEFAULT (NULL),
    [filesize] [int] NULL CONSTRAINT [DF__res_mail__filesi__5AB9788F]  DEFAULT (NULL),
    [is_paper] [char](1) NULL CONSTRAINT [DF__res_mail__is_pap__5BAD9CC8]  DEFAULT (NULL),
    [page_count] [int] NULL CONSTRAINT [DF__res_mail__page_c__5CA1C101]  DEFAULT (NULL),
    [scan_date] [datetime] NULL CONSTRAINT [DF__res_mail__scan_d__5D95E53A]  DEFAULT (NULL),
    [scan_user] [varchar](50) NULL CONSTRAINT [DF__res_mail__scan_u__5E8A0973]  DEFAULT (NULL),
    [scan_location] [varchar](255) NULL CONSTRAINT [DF__res_mail__scan_l__5F7E2DAC]  DEFAULT (NULL),
    [scan_wkstation] [varchar](255) NULL CONSTRAINT [DF__res_mail__scan_w__607251E5]  DEFAULT (NULL),
    [scan_batch] [varchar](50) NULL CONSTRAINT [DF__res_mail__scan_b__6166761E]  DEFAULT (NULL),
    [burn_batch] [varchar](50) NULL CONSTRAINT [DF__res_mail__burn_b__625A9A57]  DEFAULT (NULL),
    [scan_postmark] [varchar](50) NULL CONSTRAINT [DF__res_mail__scan_p__634EBE90]  DEFAULT (NULL),
    [envelop_id] [int] NULL CONSTRAINT [DF__res_mail__envelo__6442E2C9]  DEFAULT (NULL),
    [status] [varchar](3) NULL CONSTRAINT [DF__res_mail__status__65370702]  DEFAULT (NULL),
    [destination] [varchar](50) NULL CONSTRAINT [DF__res_mail__destin__662B2B3B]  DEFAULT (NULL),
    [approver] [varchar](50) NULL CONSTRAINT [DF__res_mail__approv__671F4F74]  DEFAULT (NULL),
    [validation_date] [datetime] NULL CONSTRAINT [DF__res_mail__valida__681373AD]  DEFAULT (NULL),
    [work_batch] [int] NULL CONSTRAINT [DF__res_mail__work_b__690797E6]  DEFAULT (NULL),
    [origin] [varchar](50) NULL CONSTRAINT [DF__res_mail__origin__69FBBC1F]  DEFAULT (NULL),
    [is_ingoing] [char](1) NULL CONSTRAINT [DF__res_mail__is_ing__6AEFE058]  DEFAULT (NULL),
    [priority] [smallint] NULL CONSTRAINT [DF__res_mail__priori__6BE40491]  DEFAULT (NULL),
    [tablename] [varchar](32) NULL CONSTRAINT [DF__res_mail__tablen__6CD828CA]  DEFAULT ('res_mail'),
    [custom_t1] [text] NULL,
    [custom_n1] [int] NULL CONSTRAINT [DF__res_mail__custom__6DCC4D03]  DEFAULT (NULL),
    [custom_f1] [decimal](10, 0) NULL CONSTRAINT [DF__res_mail__custom__6EC0713C]  DEFAULT (NULL),
    [custom_d1] [datetime] NULL CONSTRAINT [DF__res_mail__custom__6FB49575]  DEFAULT (NULL),
    [custom_t2] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__70A8B9AE]  DEFAULT (NULL),
    [custom_n2] [int] NULL CONSTRAINT [DF__res_mail__custom__719CDDE7]  DEFAULT (NULL),
    [custom_f2] [decimal](10, 0) NULL CONSTRAINT [DF__res_mail__custom__72910220]  DEFAULT (NULL),
    [custom_d2] [datetime] NULL CONSTRAINT [DF__res_mail__custom__73852659]  DEFAULT (NULL),
    [custom_t3] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__74794A92]  DEFAULT (NULL),
    [custom_n3] [int] NULL CONSTRAINT [DF__res_mail__custom__756D6ECB]  DEFAULT (NULL),
    [custom_f3] [decimal](10, 0) NULL CONSTRAINT [DF__res_mail__custom__76619304]  DEFAULT (NULL),
    [custom_d3] [datetime] NULL CONSTRAINT [DF__res_mail__custom__7755B73D]  DEFAULT (NULL),
    [custom_t4] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__7849DB76]  DEFAULT (NULL),
    [custom_n4] [int] NULL CONSTRAINT [DF__res_mail__custom__793DFFAF]  DEFAULT (NULL),
    [custom_f4] [decimal](10, 0) NULL CONSTRAINT [DF__res_mail__custom__7A3223E8]  DEFAULT (NULL),
    [custom_d4] [datetime] NULL CONSTRAINT [DF__res_mail__custom__7B264821]  DEFAULT (NULL),
    [custom_t5] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__7C1A6C5A]  DEFAULT (NULL),
    [custom_n5] [int] NULL CONSTRAINT [DF__res_mail__custom__7D0E9093]  DEFAULT (NULL),
    [custom_f5] [decimal](10, 0) NULL CONSTRAINT [DF__res_mail__custom__7E02B4CC]  DEFAULT (NULL),
    [custom_d5] [datetime] NULL CONSTRAINT [DF__res_mail__custom__7EF6D905]  DEFAULT (NULL),
    [custom_t6] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__7FEAFD3E]  DEFAULT (NULL),
    [custom_d6] [datetime] NULL CONSTRAINT [DF__res_mail__custom__00DF2177]  DEFAULT (NULL),
    [custom_t7] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__01D345B0]  DEFAULT (NULL),
    [custom_d7] [datetime] NULL CONSTRAINT [DF__res_mail__custom__02C769E9]  DEFAULT (NULL),
    [custom_t8] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__03BB8E22]  DEFAULT (NULL),
    [custom_d8] [datetime] NULL CONSTRAINT [DF__res_mail__custom__04AFB25B]  DEFAULT (NULL),
    [custom_t9] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__05A3D694]  DEFAULT (NULL),
    [custom_d9] [datetime] NULL CONSTRAINT [DF__res_mail__custom__0697FACD]  DEFAULT (NULL),
    [custom_t10] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__078C1F06]  DEFAULT (NULL),
    [custom_d10] [datetime] NULL CONSTRAINT [DF__res_mail__custom__0880433F]  DEFAULT (NULL),
    [custom_t11] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__09746778]  DEFAULT (NULL),
    [custom_d11] [datetime] NULL CONSTRAINT [DF__res_mail__custom__0A688BB1]  DEFAULT (NULL),
    [custom_t12] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__0B5CAFEA]  DEFAULT (NULL),
    [custom_d12] [datetime] NULL CONSTRAINT [DF__res_mail__custom__0C50D423]  DEFAULT (NULL),
    [custom_t13] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__0D44F85C]  DEFAULT (NULL),
    [custom_d13] [datetime] NULL CONSTRAINT [DF__res_mail__custom__0E391C95]  DEFAULT (NULL),
    [custom_t14] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__0F2D40CE]  DEFAULT (NULL),
    [custom_d14] [datetime] NULL CONSTRAINT [DF__res_mail__custom__10216507]  DEFAULT (NULL),
    [custom_t15] [varchar](255) NULL CONSTRAINT [DF__res_mail__custom__11158940]  DEFAULT (NULL),
 CONSTRAINT [PK__res_mail__489AC854] PRIMARY KEY CLUSTERED
(
    [res_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[society]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[society](
    [society_id] [int] IDENTITY(1,1) NOT NULL,
    [society_label] [varchar](50) NULL CONSTRAINT [DF__society__society__1387E197]  DEFAULT (NULL),
    [society_sysinfo_id] [varchar](255) NULL,
 CONSTRAINT [PK__society__1293BD5E] PRIMARY KEY CLUSTERED
(
    [society_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[specialites_diplomes]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[specialites_diplomes](
    [specialite_diplome_id] [int] IDENTITY(1,1) NOT NULL,
    [specialite_diplome_label] [varchar](50) NULL CONSTRAINT [DF__specialit__speci__16644E42]  DEFAULT (NULL),
    [enabled] [char](1) NOT NULL CONSTRAINT [DF__specialit__enabl__1758727B]  DEFAULT ('Y'),
 CONSTRAINT [PK__specialites_dipl__15702A09] PRIMARY KEY CLUSTERED
(
    [specialite_diplome_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[doctypes_first_level]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[doctypes_first_level](
    [doctypes_first_level_id] [int] IDENTITY(1,1) NOT NULL,
    [doctypes_first_level_label] [varchar](255) NOT NULL,
    [enabled] [char](1) NOT NULL CONSTRAINT [DF__doctypes___enabl__44FF419A]  DEFAULT ('Y'),
 CONSTRAINT [PK__doctypes_first_l__440B1D61] PRIMARY KEY CLUSTERED
(
    [doctypes_first_level_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[doctypes_second_level]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[doctypes_second_level](
    [doctypes_second_level_id] [int] IDENTITY(1,1) NOT NULL,
    [doctypes_second_level_label] [varchar](255) NOT NULL,
    [doctypes_first_level_id] [int] NOT NULL,
    [enabled] [char](1) NOT NULL CONSTRAINT [DF__doctypes___enabl__47DBAE45]  DEFAULT ('Y'),
 CONSTRAINT [PK__doctypes_second___46E78A0C] PRIMARY KEY CLUSTERED
(
    [doctypes_second_level_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[foldertypes]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[foldertypes](
    [foldertype_id] [int] IDENTITY(1,1) NOT NULL,
    [foldertype_label] [varchar](255) NOT NULL,
    [maarch_comment] [varchar](255) NULL,
    [retention_time] [varchar](50) NULL CONSTRAINT [DF__foldertyp__reten__05D8E0BE]  DEFAULT (NULL),
    [custom_d1] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__06CD04F7]  DEFAULT ('0000000000'),
    [custom_f1] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__07C12930]  DEFAULT ('0000000000'),
    [custom_n1] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__08B54D69]  DEFAULT ('0000000000'),
    [custom_t1] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__09A971A2]  DEFAULT ('0000000000'),
    [custom_d2] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__0A9D95DB]  DEFAULT ('0000000000'),
    [custom_f2] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__0B91BA14]  DEFAULT ('0000000000'),
    [custom_n2] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__0C85DE4D]  DEFAULT ('0000000000'),
    [custom_t2] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__0D7A0286]  DEFAULT ('0000000000'),
    [custom_d3] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__0E6E26BF]  DEFAULT ('0000000000'),
    [custom_f3] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__0F624AF8]  DEFAULT ('0000000000'),
    [custom_n3] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__10566F31]  DEFAULT ('0000000000'),
    [custom_t3] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__114A936A]  DEFAULT ('0000000000'),
    [custom_d4] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__123EB7A3]  DEFAULT ('0000000000'),
    [custom_f4] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__1332DBDC]  DEFAULT ('0000000000'),
    [custom_n4] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__14270015]  DEFAULT ('0000000000'),
    [custom_t4] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__151B244E]  DEFAULT ('0000000000'),
    [custom_d5] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__160F4887]  DEFAULT ('0000000000'),
    [custom_f5] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__17036CC0]  DEFAULT ('0000000000'),
    [custom_n5] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__17F790F9]  DEFAULT ('0000000000'),
    [custom_t5] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__18EBB532]  DEFAULT ('0000000000'),
    [custom_d6] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__19DFD96B]  DEFAULT ('0000000000'),
    [custom_t6] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__1AD3FDA4]  DEFAULT ('0000000000'),
    [custom_d7] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__1BC821DD]  DEFAULT ('0000000000'),
    [custom_t7] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__1CBC4616]  DEFAULT ('0000000000'),
    [custom_d8] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__1DB06A4F]  DEFAULT ('0000000000'),
    [custom_t8] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__1EA48E88]  DEFAULT ('0000000000'),
    [custom_d9] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__1F98B2C1]  DEFAULT ('0000000000'),
    [custom_t9] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__208CD6FA]  DEFAULT ('0000000000'),
    [custom_d10] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__2180FB33]  DEFAULT ('0000000000'),
    [custom_t10] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__22751F6C]  DEFAULT ('0000000000'),
    [custom_t11] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__236943A5]  DEFAULT ('0000000000'),
    [custom_t12] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__245D67DE]  DEFAULT ('0000000000'),
    [custom_t13] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__25518C17]  DEFAULT ('0000000000'),
    [custom_t14] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__2645B050]  DEFAULT ('0000000000'),
    [custom_t15] [varchar](10) NULL CONSTRAINT [DF__foldertyp__custo__2739D489]  DEFAULT ('0000000000'),
 CONSTRAINT [PK__foldertypes__04E4BC85] PRIMARY KEY CLUSTERED
(
    [foldertype_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[emplois]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[emplois](
    [emploi_id] [int] IDENTITY(1,1) NOT NULL,
    [emploi_code] [varchar](50) NULL CONSTRAINT [DF__emplois__emploi___4AB81AF0]  DEFAULT (NULL),
    [emploi_label] [varchar](50) NULL CONSTRAINT [DF__emplois__emploi___4BAC3F29]  DEFAULT (NULL),
 CONSTRAINT [PK__emplois__49C3F6B7] PRIMARY KEY CLUSTERED
(
    [emploi_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[folders]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[folders](
    [folders_system_id] [int] IDENTITY(1,1) NOT NULL,
    [folder_id] [varchar](255) NOT NULL,
    [foldertype_id] [int] NULL CONSTRAINT [DF__folders__foldert__5070F446]  DEFAULT (NULL),
    [parent_id] [int] NULL CONSTRAINT [DF__folders__parent___5165187F]  DEFAULT ('0'),
    [folder_name] [varchar](255) NULL CONSTRAINT [DF__folders__folder___52593CB8]  DEFAULT (NULL),
    [subject] [varchar](255) NULL CONSTRAINT [DF__folders__subject__534D60F1]  DEFAULT (NULL),
    [description] [varchar](255) NULL CONSTRAINT [DF__folders__descrip__5441852A]  DEFAULT (NULL),
    [author] [varchar](255) NULL CONSTRAINT [DF__folders__author__5535A963]  DEFAULT (NULL),
    [typist] [varchar](255) NULL CONSTRAINT [DF__folders__typist__5629CD9C]  DEFAULT (NULL),
    [status] [varchar](50) NOT NULL CONSTRAINT [DF__folders__status__571DF1D5]  DEFAULT ('NEW'),
    [folder_level] [int] NULL CONSTRAINT [DF__folders__folder___5812160E]  DEFAULT ('1'),
    [creation_date] [datetime] NOT NULL,
    [folder_out_id] [int] NULL CONSTRAINT [DF__folders__folder___59063A47]  DEFAULT (NULL),
    [custom_t1] [varchar](255) NULL CONSTRAINT [DF__folders__custom___59FA5E80]  DEFAULT (NULL),
    [custom_n1] [int] NULL CONSTRAINT [DF__folders__custom___5AEE82B9]  DEFAULT (NULL),
    [custom_f1] [decimal](10, 0) NULL CONSTRAINT [DF__folders__custom___5BE2A6F2]  DEFAULT (NULL),
    [custom_d1] [datetime] NULL CONSTRAINT [DF__folders__custom___5CD6CB2B]  DEFAULT (NULL),
    [custom_t2] [varchar](255) NULL CONSTRAINT [DF__folders__custom___5DCAEF64]  DEFAULT (NULL),
    [custom_n2] [int] NULL CONSTRAINT [DF__folders__custom___5EBF139D]  DEFAULT (NULL),
    [custom_f2] [decimal](10, 0) NULL CONSTRAINT [DF__folders__custom___5FB337D6]  DEFAULT (NULL),
    [custom_d2] [datetime] NULL CONSTRAINT [DF__folders__custom___60A75C0F]  DEFAULT (NULL),
    [custom_t3] [varchar](255) NULL CONSTRAINT [DF__folders__custom___619B8048]  DEFAULT (NULL),
    [custom_n3] [int] NULL CONSTRAINT [DF__folders__custom___628FA481]  DEFAULT (NULL),
    [custom_f3] [decimal](10, 0) NULL CONSTRAINT [DF__folders__custom___6383C8BA]  DEFAULT (NULL),
    [custom_d3] [datetime] NULL CONSTRAINT [DF__folders__custom___6477ECF3]  DEFAULT (NULL),
    [custom_t4] [varchar](255) NULL CONSTRAINT [DF__folders__custom___656C112C]  DEFAULT (NULL),
    [custom_n4] [int] NULL CONSTRAINT [DF__folders__custom___66603565]  DEFAULT (NULL),
    [custom_f4] [decimal](10, 0) NULL CONSTRAINT [DF__folders__custom___6754599E]  DEFAULT (NULL),
    [custom_d4] [datetime] NULL CONSTRAINT [DF__folders__custom___68487DD7]  DEFAULT (NULL),
    [custom_t5] [varchar](255) NULL CONSTRAINT [DF__folders__custom___693CA210]  DEFAULT (NULL),
    [custom_n5] [int] NULL CONSTRAINT [DF__folders__custom___6A30C649]  DEFAULT (NULL),
    [custom_f5] [decimal](10, 0) NULL CONSTRAINT [DF__folders__custom___6B24EA82]  DEFAULT (NULL),
    [custom_d5] [datetime] NULL CONSTRAINT [DF__folders__custom___6C190EBB]  DEFAULT (NULL),
    [custom_t6] [varchar](255) NULL CONSTRAINT [DF__folders__custom___6D0D32F4]  DEFAULT (NULL),
    [custom_d6] [datetime] NULL CONSTRAINT [DF__folders__custom___6E01572D]  DEFAULT (NULL),
    [custom_t7] [varchar](255) NULL CONSTRAINT [DF__folders__custom___6EF57B66]  DEFAULT (NULL),
    [custom_d7] [datetime] NULL CONSTRAINT [DF__folders__custom___6FE99F9F]  DEFAULT (NULL),
    [custom_t8] [varchar](255) NULL CONSTRAINT [DF__folders__custom___70DDC3D8]  DEFAULT (NULL),
    [custom_d8] [datetime] NULL CONSTRAINT [DF__folders__custom___71D1E811]  DEFAULT (NULL),
    [custom_t9] [varchar](255) NULL CONSTRAINT [DF__folders__custom___72C60C4A]  DEFAULT (NULL),
    [custom_d9] [datetime] NULL CONSTRAINT [DF__folders__custom___73BA3083]  DEFAULT (NULL),
    [custom_t10] [varchar](255) NULL CONSTRAINT [DF__folders__custom___74AE54BC]  DEFAULT (NULL),
    [custom_d10] [datetime] NULL CONSTRAINT [DF__folders__custom___75A278F5]  DEFAULT (NULL),
    [custom_t11] [varchar](255) NULL CONSTRAINT [DF__folders__custom___76969D2E]  DEFAULT (NULL),
    [custom_d11] [datetime] NULL CONSTRAINT [DF__folders__custom___778AC167]  DEFAULT (NULL),
    [custom_t12] [varchar](255) NULL CONSTRAINT [DF__folders__custom___787EE5A0]  DEFAULT (NULL),
    [custom_d12] [datetime] NULL CONSTRAINT [DF__folders__custom___797309D9]  DEFAULT (NULL),
    [custom_t13] [varchar](255) NULL CONSTRAINT [DF__folders__custom___7A672E12]  DEFAULT (NULL),
    [custom_d13] [datetime] NULL CONSTRAINT [DF__folders__custom___7B5B524B]  DEFAULT (NULL),
    [custom_t14] [varchar](255) NULL CONSTRAINT [DF__folders__custom___7C4F7684]  DEFAULT (NULL),
    [custom_d14] [datetime] NULL CONSTRAINT [DF__folders__custom___7D439ABD]  DEFAULT (NULL),
    [custom_t15] [varchar](255) NULL CONSTRAINT [DF__folders__custom___7E37BEF6]  DEFAULT (NULL),
    [is_complete] [char](1) NULL CONSTRAINT [DF__folders__is_comp__7F2BE32F]  DEFAULT ('N'),
    [is_folder_out] [char](1) NULL CONSTRAINT [DF__folders__is_fold__00200768]  DEFAULT ('N'),
 CONSTRAINT [PK__folders__4F7CD00D] PRIMARY KEY CLUSTERED
(
    [folders_system_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[autofoldering_security]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[autofoldering_security](
    [group_id] [varchar](50) NOT NULL,
    [tree_id] [varchar](50) NOT NULL,
    [where_clause] [text] NOT NULL,
PRIMARY KEY CLUSTERED
(
    [group_id] ASC,
    [tree_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[baskets]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[baskets](
    [coll_id] [varchar](32) NOT NULL,
    [basket_id] [varchar](32) NOT NULL,
    [basket_name] [varchar](50) NOT NULL,
    [basket_desc] [varchar](255) NOT NULL,
    [basket_clause] [text] NOT NULL,
    [is_generic] [varchar](6) NOT NULL DEFAULT ('NO'),
    [enabled] [char](1) NOT NULL DEFAULT ('Y'),
PRIMARY KEY CLUSTERED
(
    [coll_id] ASC,
    [basket_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[security]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[security](
    [security_id] [int] NOT NULL,
    [group_id] [varchar](32) NOT NULL,
    [coll_id] [varchar](32) NOT NULL,
    [where_clause] [varchar](255) NULL DEFAULT (NULL),
    [comment] [text] NULL,
    [can_insert] [char](1) NOT NULL DEFAULT ('N'),
    [can_update] [char](1) NOT NULL DEFAULT ('N'),
    [can_delete] [char](1) NOT NULL DEFAULT ('N'),
    [rights_bitmask] [int] NOT NULL,
    [mr_start_date] [datetime] DEFAULT NULL,
    [mr_stop_date] [datetime] DEFAULT NULL,
    [where_target] [varchar](15) DEFAULT ('DOC'),
PRIMARY KEY CLUSTERED
(
    [security_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[docservers]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[docservers](
    [docserver_id] [varchar](32) NOT NULL DEFAULT ('1'),
    [device_type] [varchar](32) NULL DEFAULT (NULL),
    [device_label] [varchar](255) NULL DEFAULT (NULL),
    [is_readonly] [char](1) NOT NULL DEFAULT ('N'),
    [enabled] [char](1) NOT NULL DEFAULT ('Y'),
    [size_limit] [int] NOT NULL DEFAULT ('0'),
    [actual_size] [int] NOT NULL DEFAULT ('0'),
    [path_template] [varchar](255) NOT NULL,
    [ext_docserver_info] [varchar](255) NULL DEFAULT (NULL),
    [chain_before] [varchar](32) NULL DEFAULT (NULL),
    [chain_after] [varchar](32) NULL DEFAULT (NULL),
    [creation_date] [datetime] NOT NULL,
    [closing_date] [datetime] NULL DEFAULT (NULL),
PRIMARY KEY CLUSTERED
(
    [docserver_id] ASC
)WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.views WHERE object_id = OBJECT_ID(N'[dbo].[res_view]'))
EXEC dbo.sp_executesql @statement = N'CREATE VIEW [dbo].[res_view] AS
    SELECT r.tablename, r.res_id, r.type_id, d.description AS type_label, r.format, r.typist, r.creation_date, r.relation, r.docserver_id, r.folders_system_id, f.folder_id, r.path, r.filename, r.fingerprint, r.filesize, r.status, r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2, r.custom_t3 AS doc_custom_t3, r.custom_t4 AS doc_custom_t4, r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6, r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8, r.custom_t9 AS doc_custom_t9, r.custom_t10 AS doc_custom_t10, r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12, r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14, r.custom_t15 AS doc_custom_t15, r.custom_d1 AS doc_custom_d1, r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3, r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5, r.custom_d6 AS doc_custom_d6, r.custom_d7 AS doc_custom_d7, r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9, r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1, r.custom_n2 AS doc_custom_n2, r.custom_n3 AS doc_custom_n3, r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5, r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2, r.custom_f3 AS doc_custom_f3, r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5, f.foldertype_id, ft.foldertype_label, f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2, f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4, f.custom_t5 AS fold_custom_t5, f.custom_t6 AS fold_custom_t6, f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8, f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10, f.custom_t11 AS fold_custom_t11, f.custom_t12 AS fold_custom_t12, f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14, f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1, f.custom_d2 AS fold_custom_d2, f.custom_d3 AS fold_custom_d3, f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5, f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7, f.custom_d8 AS fold_custom_d8, f.custom_d9 AS fold_custom_d9, f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1, f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3, f.custom_n4 AS fold_custom_n4, f.custom_n5 AS fold_custom_n5, f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2, f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4, f.custom_f5 AS fold_custom_f5, f.is_complete AS fold_complete, f.status AS fold_status FROM res_x r, folders f, doctypes d, foldertypes ft WHERE ((((r.folders_system_id = f.folders_system_id) AND (r.type_id = d.type_id)) AND (f.foldertype_id = ft.foldertype_id)) AND ((f.status)<> ''DEL''));
'
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.views WHERE object_id = OBJECT_ID(N'[dbo].[res_view_mail]'))
EXEC dbo.sp_executesql @statement = N'CREATE VIEW [dbo].[res_view_mail] AS
    SELECT r.tablename, r.res_id, r.type_id, d.description AS type_label, d.doctypes_first_level_id, dfl.doctypes_first_level_label, d.doctypes_second_level_id, dsl.doctypes_second_level_label, r.format, r.typist, r.creation_date, r.relation, r.docserver_id, r.folders_system_id, f.folder_id, r.path, r.filename, r.fingerprint, r.filesize, r.status, r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2, r.custom_t3 AS doc_custom_t3, r.custom_t4 AS doc_custom_t4, r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6, r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8, r.custom_t9 AS doc_custom_t9, r.custom_t10 AS doc_custom_t10, r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12, r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14, r.custom_t15 AS doc_custom_t15, r.custom_d1 AS doc_custom_d1, r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3, r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5, r.custom_d6 AS doc_custom_d6, r.custom_d7 AS doc_custom_d7, r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9, r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1, r.custom_n2 AS doc_custom_n2, r.custom_n3 AS doc_custom_n3, r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5, r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2, r.custom_f3 AS doc_custom_f3, r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5, f.foldertype_id, ft.foldertype_label, f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2, f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4, f.custom_t5 AS fold_custom_t5, f.custom_t6 AS fold_custom_t6, f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8, f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10, f.custom_t11 AS fold_custom_t11, f.custom_t12 AS fold_custom_t12, f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14, f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1, f.custom_d2 AS fold_custom_d2, f.custom_d3 AS fold_custom_d3, f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5, f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7, f.custom_d8 AS fold_custom_d8, f.custom_d9 AS fold_custom_d9, f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1, f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3, f.custom_n4 AS fold_custom_n4, f.custom_n5 AS fold_custom_n5, f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2, f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4, f.custom_f5 AS fold_custom_f5, f.is_complete AS fold_complete, f.status AS fold_status FROM res_mail r, folders f, doctypes d, foldertypes ft, doctypes_first_level dfl, doctypes_second_level dsl WHERE ((((((r.folders_system_id = f.folders_system_id) AND (r.type_id = d.type_id)) AND (f.foldertype_id = ft.foldertype_id)) AND ((f.status) <> ''DEL'')) AND (d.doctypes_first_level_id = dfl.doctypes_first_level_id)) AND (d.doctypes_second_level_id = dsl.doctypes_second_level_id));'

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

