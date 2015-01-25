<h4> valeurs de la colonne typeofdata : </h4>
<ul><li>voir <a href="https://wiki.vtiger.com/index.php/TypeOfData" target="_blank"> ici </a></li>
</ul>
<div lang="en" dir="ltr" class="mw-content-ltr"><h2> <span class="mw-headline" id="typeofdata_for_Fields_in_Modules"> typeofdata for Fields in Modules  </span></h2>
<p><i>Contributors: Joe Bordes</i> 
</p><p><i>UNDER CONSTRUCTION</i> 
</p><p><br> 
</p>
<table border="1">

<tbody><tr>
<th> typeofdata
</th>
<th> data type
</th></tr>
<tr>
<td> C
</td>
<td> Checkbox/Boolean
</td></tr>
<tr>
<td> D
</td>
<td> Date
</td></tr>
<tr>
<td> DT
</td>
<td> DateTime
</td></tr>
<tr>
<td> E
</td>
<td> EMail
</td></tr>
<tr>
<td> I
</td>
<td> Integer
</td></tr>
<tr>
<td> N
</td>
<td> Number
</td></tr>
<tr>
<td> NN
</td>
<td> Negative Number
</td></tr>
<tr>
<td> O
</td>
<td> RecurringType/Duration_minutes
</td></tr>
<tr>
<td> P
</td>
<td> Password
</td></tr>
<tr>
<td> T
</td>
<td> Time
</td></tr>
<tr>
<td> V
</td>
<td> Varchar
</td></tr></tbody></table>
<p>Examples from 5.1.0 database: 
</p>
<table border="1">

<tbody><tr>
<th> typeofdata
</th></tr>
<tr>
<td> C~O
</td></tr>
<tr>
<td> DT~M~time_start
</td></tr>
<tr>
<td> DT~M~time_start~Time Start
</td></tr>
<tr>
<td> D~M
</td></tr>
<tr>
<td> D~M~OTH~GE~date_start~Start Date &amp; Time
</td></tr>
<tr>
<td> D~O
</td></tr>
<tr>
<td> D~O~OTH~GE~sales_start_date~Sales Start Date
</td></tr>
<tr>
<td> D~O~OTH~GE~start_date~Start Date
</td></tr>
<tr>
<td> D~O~OTH~GE~support_start_date~Support Start Date
</td></tr>
<tr>
<td> D~O~OTH~G~start_period~Start Period
</td></tr>
<tr>
<td> E~M
</td></tr>
<tr>
<td> E~O
</td></tr>
<tr>
<td> I~M
</td></tr>
<tr>
<td> I~O
</td></tr>
<tr>
<td> NN~O
</td></tr>
<tr>
<td> N~M
</td></tr>
<tr>
<td> N~O
</td></tr>
<tr>
<td> N~O~10,2
</td></tr>
<tr>
<td> N~O~2,2
</td></tr>
<tr>
<td> N~O~2~2
</td></tr>
<tr>
<td> O~O
</td></tr>
<tr>
<td> P~M
</td></tr>
<tr>
<td> T~M
</td></tr>
<tr>
<td> T~O
</td></tr>
<tr>
<td> V~0
</td></tr>
<tr>
<td> V~M
</td></tr>
<tr>
<td> V~O
</td></tr>
<tr>
<td> V~O~LE~150
</td></tr>
<tr>
<td> V~O~LE~16
</td></tr>
<tr>
<td> V~O~LE~25
</td></tr>
<tr>
<td> V~O~LE~4
</td></tr>
<tr>
<td>
<p>V~O~LE~9 
</p>
</td></tr></tbody></table>
<p>In the above examples: 
</p><p>M -&gt; Indicates Mandatory field 
</p><p>O -&gt; Indicates Optional field
</p></div>