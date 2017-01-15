INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource) 
VALUES (3, 'AppelTel', 'Appel téléphonique', 
'<p><font size="\\&quot;5\\&quot;"><strong>APPEL TELEPHONIQUE</strong></font></p>
<p><font size="\\&quot;2\\&quot;">Bonjour,</font></p>
<p><font size="\\&quot;2\\&quot;">Vous avez re&ccedil;u un appel t&eacute;l&eacute;phonique dont voici les informations :</font></p>
<ul>
<li><font size="\\&quot;2\\&quot;">Date : </font></li>
<li><font size="\\&quot;2\\&quot;">Heure :</font></li>
<li><font size="\\&quot;2\\&quot;">Soci&eacute;t&eacute; :</font></li>
<li><font size="\\&quot;2\\&quot;">Contact :</font></li>
</ul>
<p><font size="\\&quot;2\\&quot;">Notes : </font></p>',
'HTML', NULL, NULL, '', '');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource) 
VALUES (2, 'Notifications événement', 'Notifications des événements système', 
'<p><font face="verdana,geneva" size="1">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p><font face="verdana,geneva" size="1"> </font></p>
<p><font face="verdana,geneva" size="1">Voici la liste des &eacute###v&eacute###nements de l''application qui vous sont notifi&eacute###s ([notification.description]) :</font></p>
<table style="width: 800px### height: 36px###" border="0" cellspacing="1" cellpadding="1">
<tbody>
<tr>
<td style="width: 150px### background-color: #0099ff###"><font face="verdana,geneva" size="1"><strong><font color="#FFFFFF">Date</font></strong></font></td>
<td style="width: 150px### background-color: #0099ff###"><font face="verdana,geneva" size="1"><strong><font color="#FFFFFF">Utilisateur </font></strong></font><font face="verdana,geneva" size="1"><strong></strong></font></td>
<td style="width: 500px### background-color: #0099ff###"><font face="verdana,geneva" size="1"><strong><font color="#FFFFFF">Description</font></strong></font></td>
</tr>
<tr>
<td><font face="verdana,geneva" size="1">[events.event_date###block=tr###frm=dd/mm/yyyy hh:nn:ss]</font></td>
<td><font face="verdana,geneva" size="1">[events.user_id]</font></td>
<td><font face="verdana,geneva" size="1">[events.event_info]</font></td>
</tr>
</tbody>
</table>', 
'HTML', NULL, NULL, '', 'notif_events');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource) 
VALUES (4, '[notification] Diffusion de courrier en copie', '[notification] Diffusion de courrier en copie', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2">Voici la liste des nouveaux courriers qui vous ont été envoyés en copie :</font></p>
<table style="border: 1pt solid #000000### width: 1582px### height: 77px###" border="1" cellspacing="1" cellpadding="5" frame="box">
<tbody>
<tr>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Référence</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Origine</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Emetteur</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Date</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Objet</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Type</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#FFFFFF"><strong>Liens</strong></font></td>
</tr>
<tr>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.res_id]</font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.contact_society][res_letterbox.contact_firstname][res_letterbox.contact_lastname][res_letterbox.function][res_letterbox.address_num][res_letterbox.address_street][res_letterbox.address_postal_code][res_letterbox.address_town]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date###block=tr###frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="[res_letterbox.linktodetail]" name="detail">detail</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource) 
VALUES (5, '[notification] Alerte 2', '[notification] Alerte 2', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2">Voici la liste des courriers dont la date limite de traitement est dépassée :n</font></p>
<table style="border: 1pt solid #000000### width: 1582px### height: 77px###" border="1" cellspacing="1" cellpadding="5" frame="box">
<tbody>
<tr>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Référence</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Origine</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Emetteur</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Date</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Objet</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Type</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#FFFFFF"><strong>Liens</strong></font></td>
</tr>
<tr>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.res_id]</font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.contact_society][res_letterbox.contact_firstname][res_letterbox.contact_lastname][res_letterbox.function][res_letterbox.address_num][res_letterbox.address_street][res_letterbox.address_postal_code][res_letterbox.address_town]</font></p>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.tag_label]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date###block=tr###frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="res_letterbox.linktoprocess" name="traiter">traiter</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource) 
VALUES (6, '[notification] Alerte 1', '[notification] Alerte 1', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2"> </font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2">Voici la liste des courriers toujours en attente de traitement :</font></p>
<p> </p>
<table style="border: 1pt solid #000000### width: 1582px### height: 77px###" border="1" cellspacing="1" cellpadding="5" frame="box">
<tbody>
<tr>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Référence</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Origine</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Emetteur</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Date</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Objet</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Type</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#FFFFFF"><strong>Liens</strong></font></td>
</tr>
<tr>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.res_id]</font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.contact_society][res_letterbox.contact_firstname][res_letterbox.contact_lastname][res_letterbox.function][res_letterbox.address_num][res_letterbox.address_street][res_letterbox.address_postal_code][res_letterbox.address_town]</font></p>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.tag_label]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date###block=tr###frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="res_letterbox.linktoprocess" name="traiter">traiter</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource) 
VALUES (7, '[notification] Diffusion de courrier', '[notification] Diffusion de courrier à traiter', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2"> </font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2">Voici la liste des nouveaux courriers qui vous ont été envoyés :</font></p>
<p> </p>
<table style="border: 1pt solid #000000### width: 1582px### height: 77px###" border="1" cellspacing="1" cellpadding="5" frame="box">
<tbody>
<tr>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Référence</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Origine</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Emetteur</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Date</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Objet</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Type</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#FFFFFF"><strong>Liens</strong></font></td>
</tr>
<tr>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.res_id]</font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.contact_society][res_letterbox.contact_firstname][res_letterbox.contact_lastname][res_letterbox.function][res_letterbox.address_num][res_letterbox.address_street][res_letterbox.address_postal_code][res_letterbox.address_town]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date###block=tr###frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="[res_letterbox.linktodetail]" name="detail">detail</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource) 
VALUES (8, '[notification] Nouvelle annotation', '[notification] Nouvelle annotation', '<p><font face="verdana,geneva" size="2">Bonjour [recipient.firstname] [recipient.lastname], [recipient.text]</font></p>
<p>&nbsp###</p>
<p><font face="verdana,geneva" size="2"> </font></p>
<p>&nbsp###</p>
<p><font face="verdana,geneva" size="2">Voici la liste des notes pour les courriers suivants :</font></p>
<p>&nbsp###</p>
<table style="width: 982px### height: 77px###" border="1" cellspacing="3" cellpadding="3" frame="box">
<tbody>
<tr>
<td><strong>Reference</strong></td>
<td><strong>Num</strong></td>
<td><strong>Date</strong></td>
<td><strong>Objet</strong></td>
<td><strong>Note</strong></td>
<td><strong>Ajout&eacute### par</strong></td>
<td><strong>Contact</strong></td>
<td><strong>Liens</strong></td>
</tr>
<tr>
<td>[notes.identifier]</td>
<td>[notes.# ###frm=0000]</td>
<td>[notes.doc_date###block=tr###frm=dd/mm/yyyy]</td>
<td>[notes.subject]</td>
<td>[notes.note_text]</td>
<td>[notes.user_id]</td>
<td>[notes.contact_society][notes.contact_firstname][notes.contact_lastname]</td>
<td><a href="notes.linktodetail" name="detail">d&eacute###tail</a> <a href="notes.linktodoc" name="doc">doc</a></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'notes');
