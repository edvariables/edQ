<?php
$uid = uniqid('form');
$count_rows = 30;
$maxRows = 30;

global $tree;
$csv = $tree->get_child_by_name($node['id'], 'csv');
if(is_array($csv))
	$csv = $csv['id'];
else
	$csv = 'ERROR-' . $csv;
$csv_options = "&node=" . $node['id'];
?>
<form id="<?=$uid?>" method="POST" action="view.php?id=<?=$node['id']?>&vw=file.call" autocomplete="off">
<table class="edq" style="overflow: scroll;">
	<caption style="text-align: left;"><?= $count_rows . ' ligne' . ($count_rows > 1 ? 's' : '')
		. ( $count_rows == $maxRows ? ' ou plus' : '' )?>
		<a href="view.php?id=<?=$csv?><?=$csv_options?>&vw=file.call" style="margin-left: 2em;">télécharger</a>
	</caption>
	<thead><tr>
	 <th></th>
	<th>#</th>
	<th>Nom</th>
	<th>Email</th>
	<th>Téléphone</th>
	<th>Adresse</th>
	<th>Ville</th>
	</tr></thead>
	<tbody><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '6568',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=6568">éditer</a>
	 	 </th>
		<td>6568		</td><td> BENOIT LUGAND EDDY		</td><td>		</td><td>06 46 57 26 73		</td><td>LE CLOS DE L'ESSOT		</td><td>71960 VERCHIZEUIL		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '16170',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=16170">éditer</a>
	 	 </th>
		<td>16170		</td><td> BLANC NORBERT 		</td><td>		</td><td>0622248627		</td><td>lot 5  le village		</td><td>69840 EMERINGES		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '14664',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=14664">éditer</a>
	 	 </th>
		<td>14664		</td><td> BLOUZARD JOCELYN		</td><td>		</td><td>06.10.25.76.84		</td><td>87 CHEMIN DES CROZATS		</td><td>01570 ASNIERES-SUR-SAONE		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '7266',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=7266">éditer</a>
	 	 </th>
		<td>7266		</td><td> BOIRON YOANN		</td><td>		</td><td>06.76.96.51.05		</td><td>CHEMIN DU CLOS ST PIERRE		</td><td>71850 CHARNAY LES MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '13756',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=13756">éditer</a>
	 	 </th>
		<td>13756		</td><td> BRIQ' O L'AGE DE PIERRE		</td><td>briqolagedepierre@live.fr		</td><td>06.88.71.07.12<br>03.85.37.62.08		</td><td>La Croix Blanche		</td><td>71960 SOLOGNY		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '6274',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=6274">éditer</a>
	 	 </th>
		<td>6274		</td><td> CHARVIN PIERRE		</td><td>		</td><td>		</td><td>412 route de namary		</td><td>01540 VONNAS		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '6036',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=6036">éditer</a>
	 	 </th>
		<td>6036		</td><td> DOUDET JEAN CLAUDE		</td><td>		</td><td>06 27 76 09 00		</td><td>LES CHARMES		</td><td>01190 GORREVOD		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '20874',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=20874">éditer</a>
	 	 </th>
		<td>20874		</td><td> FAILLET PASCAL		</td><td>		</td><td>0632066907		</td><td>500 rue de la villeneuve		</td><td>01290 CROTTET		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '3816',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=3816">éditer</a>
	 	 </th>
		<td>3816		</td><td> GATINET MAURICE		</td><td>		</td><td>		</td><td>		</td><td>71000 SENNECE LES MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '7248',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=7248">éditer</a>
	 	 </th>
		<td>7248		</td><td> SARAG JEAN YVES		</td><td>		</td><td>06 13 09 15 46		</td><td>396 chemin de la paniere		</td><td>71850 CHARNAY LES MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '7042',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=7042">éditer</a>
	 	 </th>
		<td>7042		</td><td> SARL A 4 MAINS		</td><td>		</td><td>06 63 38 65 62		</td><td>1469 RUE VREMONTOISE		</td><td>71000 SENNECE LES MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '11034',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=11034">éditer</a>
	 	 </th>
		<td>11034		</td><td> SARL JEROMES CONCEPT		</td><td>contact@jeromes-concept.fr		</td><td>04.74.25.61.10<br>06.65.66.06.42		</td><td>821 route de Marboz		</td><td>01440 VIRIAT		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '8462',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=8462">éditer</a>
	 	 </th>
		<td>8462		</td><td>2126 WC		</td><td>		</td><td>		</td><td>		</td><td>		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '8428',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=8428">éditer</a>
	 	 </th>
		<td>8428		</td><td>9024 XX 71		</td><td>		</td><td>		</td><td>		</td><td>		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '609',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=609">éditer</a>
	 	 </th>
		<td>609		</td><td>A.M.J. CARRIERES		</td><td>		</td><td>03.85.33.18.79.		</td><td>Rue du Mur Cidex 574		</td><td>71260 CLESSE		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '3782',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=3782">éditer</a>
	 	 </th>
		<td>3782		</td><td>A3P ENTREPRISE		</td><td>		</td><td>0680632870		</td><td>610 route de cluny		</td><td>71850 CHARNAY LES MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '5866',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=5866">éditer</a>
	 	 </th>
		<td>5866		</td><td>AANANOUCH MOHAMED		</td><td>		</td><td>06 03 00 52 09		</td><td>858 RUE VRAIMONTOISE		</td><td>71000 SENNECE LES MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '17954',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=17954">éditer</a>
	 	 </th>
		<td>17954		</td><td>ABATE ANTHONY		</td><td>		</td><td>06.71.70.37.48		</td><td>205 bd des perrières		</td><td>71000 MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '15008',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=15008">éditer</a>
	 	 </th>
		<td>15008		</td><td>ABREU GOMES ADRIEN		</td><td>		</td><td>		</td><td>		</td><td>01380 BAGE LA VILLE		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '11652',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=11652">éditer</a>
	 	 </th>
		<td>11652		</td><td>ACADEMIE DE MACON		</td><td>		</td><td>06.11.74.30.82		</td><td>HOTEL DE SENECE
41 RUE SIGORGNE
		</td><td>71000 MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '6056',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=6056">éditer</a>
	 	 </th>
		<td>6056		</td><td>ACIK FATIH		</td><td>		</td><td>0652090840		</td><td>31 rue de la girouette		</td><td>71000 MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '21068',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=21068">éditer</a>
	 	 </th>
		<td>21068		</td><td>ACL TEMPORAL		</td><td>		</td><td>06 85 05 77 44 		</td><td>12 rue municipale		</td><td>71250 CLUNY		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '12590',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=12590">éditer</a>
	 	 </th>
		<td>12590		</td><td>AD CHEMINEE		</td><td>		</td><td>0385380562		</td><td>2 RUE DE LA MADONE		</td><td>71000 SANCE		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '13834',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=13834">éditer</a>
	 	 </th>
		<td>13834		</td><td>AD CHEMINEES		</td><td>		</td><td>03.85.38.05.62		</td><td>2 rue de la madone		</td><td>71000 SANCE		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '6472',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=6472">éditer</a>
	 	 </th>
		<td>6472		</td><td>ADAMSKI ANDRE		</td><td>		</td><td>		</td><td>		</td><td>71000 MACON		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '5682',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=5682">éditer</a>
	 	 </th>
		<td>5682		</td><td>ADAPEI - FOYER HEBERGEMENT COURTES VERNOUX		</td><td>		</td><td>04.74.42.10.50<br>04.74.42.10.51 Fax		</td><td>BP 27		</td><td>01370 ST ETIENNE DU BOIS		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '1482',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=1482">éditer</a>
	 	 </th>
		<td>1482		</td><td>ADAPEI DE L'AIN - ESAT LES BROSSES		</td><td>		</td><td>04.74.47.04.70		</td><td>BP 34 VERNOUX		</td><td>01560 ST TRIVIER DE COURTES		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '446',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=446">éditer</a>
	 	 </th>
		<td>446		</td><td>AECI		</td><td>		</td><td>03.85.31.17.26.<br>06.21.68.50.45		</td><td>06.09.85.09.70 (Daniel Verdun)
ZA La Fontaine		</td><td>01290 CROTTET		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '8320',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=8320">éditer</a>
	 	 </th>
		<td>8320		</td><td>AF 038 EZ		</td><td>		</td><td>		</td><td>		</td><td>		</td></tr><tr>
	 	 <th><a onclick="$.get(this.getAttribute('href'), function(html){
	 	 	 	 $('&lt;div&gt;&lt;/div&gt;').appendTo('body').html(html).dialog({
	 	 	 	 	 title: '15376',
	 	 	 	 	 width: 'auto',
	 	 	 	 	 height: 'auto'
	 	 	 	 });
	 	 	 	});
	 	 	 return false;" href="view.php?id=1102&amp;vw=file.call&amp;f--IdContact=15376">éditer</a>
	 	 </th>
		<td>15376		</td><td>AFM CARRELAGE		</td><td>		</td><td>06.79.63.55.30		</td><td>MARCHAND FRÉDÉRIC
Nizerel		</td><td>01190 SAINT BENIGNE		</td></tr></tbody>
	<tfoot><tr><td colspan="99">30 lignes</td></tr>
	</tfoot>
</table>
</form>