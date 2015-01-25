<?php $node = node($node, __FILE__);
?>
<h3>Ajouter une couleur aux dossiers de documents</h3>

<h4>SQL</h4>
<p style="white-space:pre;">
<code>ALTER TABLE `vtiger_attachmentsfolder` ADD `uicolor` VARCHAR(16) NULL ;</code>

</p>

<h4>languages\fr_fr\Documents.php</h4>
<p style="white-space:pre;">
<code>
	'LBL_FOLDER_DESCRIPTION'       =&gt; 'Description du dossiEr'          , 
	'LBL_FOLDER_UICOLOR'            =&gt; 'Couleur d\affichage'          , </code>

</p>

<h4>modules\Documents\Documents.php, ligne 41</h4>
<p style="white-space:pre;">
<code>
	var $list_fields = Array(
				'Title'=&gt;Array('notes'=>'title'),
				'File Name'=&gt;Array('notes'=>'filename'),
				'Modified Time'=&gt;Array('crmentity'=>'modifiedtime'),
				'Assigned To' =&gt; Array('crmentity'=>'smownerid'),
				'Folder Name' =&gt; Array('attachmentsfolder'=>'folderid'),
				<b>'Folder Color' =&gt; Array('attachmentsfolder'=>'uicolor'),</b>
			);</code>
</p>

<h4>modules\Documents\Documents.php, ligne 315</h4>
<p style="white-space:pre;">
<code>
	$insertQuery = "insert into vtiger_attachmentsfolder
			<b>(`folderid`, `foldername`, `description`, `createdby`, `sequence`) </b>/*ED141010*/
			values (0,'Default','Contains all attachments for which a folder is not set',1,0)";
			     </code>
</p>

<h4>modules\Documents\models\Folder.php</h4>
<p style="white-space:pre;">
<code>public function save()
	</code>
</p>

<h4></h4>
<p style="white-space:pre;">
<code>
	</code>
</p>

<h4></h4>
<p style="white-space:pre;">
<code>
	</code>
</p>