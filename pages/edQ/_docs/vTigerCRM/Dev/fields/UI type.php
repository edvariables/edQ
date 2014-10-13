<?php
$node = node($node, __FILE__);
?><pre><h3>Valeurs des ypes de champs</h3>
Se définit dans la table vtiger_field, champ uitype

Préférés :
- 10 : module lié
- 15, 16 : picklists
- 33 : picklist de sélection multiple

ED : 
- 401 : color
- 402 : buttonset

<table border="1">

<tbody><tr>
<th> UI type
</th>
<th> Purpose
</th>
<th> vtiger Version
</th></tr>
<tr>
<td> 1
</td>
<td> A single line textfield. Changes colour when selected.
</td></tr>
<tr>
<td> 2
</td>
<td> Same as uitype 1, but this is a mandatory field. A star is present before the field label. Changes color when selected.
</td>
<td>
</td></tr>
<tr>
<td> 3
</td>
<td> An non-editable single line text field which displays auto-generated and incremental input, like invoice number. It is a mandatory field. A star is present before the field label. Changes color when selected.
</td>
<td>
</td></tr>
<tr>
<td> 4
</td>
<td> Used for generating Auto number of configured type in any module.
</td>
<td>
</td></tr>
<tr>
<td> 5
</td>
<td> Date field. Contains a link to "jsCalendar" with it which can be used to fill it in. Also displays he current users date format below it. Takes inut date based on the current users date format. Does not allow to enter invalid date like 30th february. Does not change colour on selection. Mostly used to take start date inputs.
</td></tr>
<tr>
<td> 6
</td>
<td> This is a time and date field. It allows to enter time using dropdowns and date using a link to "jscalendar". The validity of date and time is checked before entry. It is mandatory that the entered date is greater than or equal to the current date. Does not change colour on selection.
</td>
<td>
</td></tr>
<tr>
<td> 7
</td>
<td> A single line textfield which is used to take numeric input primarily. Changes colour on selection.
</td></tr>
<tr>
<td> 8
</td>
<td> Json array is stored and the value when consumed will be comma separated strings.
</td>
<td> <br>
</td></tr>
<tr>
<td> 9
</td>
<td> A single line textfield which is used to accept percentage inputs from users. Checks if the input is less than 100 or not and gives and error otherwise. Changes colour on selection.
</td></tr>
<tr>
<td> 10
</td>
<td> To create an input type of 'Linked Entity' wherein a field can be linked to one of the entity from multiple modules (eg: Member of field) -&gt; Introduced by vtlib
</td>
<td><p>&gt;= 5.1.0
</p><p>(vtlib &gt;= 2.0)</p></td></tr>
<tr>
<td> 11
</td>
<td> A single line textfield. Has no checks for the validity of input data. Changes colour on selection.
</td></tr>
<tr>
<td> 12
</td>
<td> Email id field which stores the single email id (from email address), when mail is sent from the system
</td>
<td>
</td></tr>
<tr>
<td> 13
</td>
<td> A single line textfield. Used to take the email-addresses as input from user. Checks for the validity of the entered email and gives an error if it is invalid. Changes colour on selection.
</td></tr>
<tr>
<td> 15
</td>
<td> A dropdown combo that accepts input from the value selected in it. The values in the dropdown vary from module to module and Role-based.
</td></tr>
<tr>
<td> 16
</td>
<td> A dropdown combo that accepts input from the value selected in it. The values in the dropdown vary from module to module and doesn't depend on the current user's role (non-role based).
</td>
<td> &gt;= 5.1.0<br>
</td></tr>
<tr>
<td> 17
</td>
<td> Single line textfield which is used to accept the names of websites from the users. Does not check for the validity of input. Changes colour on selection.
</td></tr>
<tr>
<td> 19
</td>
<td> Textarea used for accepting large inputs like "Description", "Solutions" etc. Changes colour on selection.
</td>
<td>
</td></tr>
<tr>
<td> 20
</td>
<td> Same as uitype 19, but a mandatory field, i.e. it has to be filled and there is a star present before the fieldlabel. Changes colour on selection.
</td>
<td>
</td></tr>
<tr>
<td> 21
</td>
<td> extarea sized around five lines. Used to take small details like "Street Address" from user as input. Changes colour on selection.
</td></tr>
<tr>
<td> 22
</td>
<td> A textarea which is used to accept the "Title" field in some modules. It is a mandatory field. A star is present before the fieldlabel. Changes colour on selection.
</td>
<td>
</td></tr>
<tr>
<td> 23
</td>
<td> Date field. Same as uitype 5, but mostly used to take end date inputs.
</td>
<td>
</td></tr>
<tr>
<td> 24
</td>
<td> Textarea sized around five lines. Primarily used to take small details like "Billing Address" from user as input. When a contact is selected, then if the user consents, the billing address is filled automatically using the contact address as billing address. Is a mandatory field. A star is present before the textlabel. Changes colour on selection.
</td>
<td>
</td></tr>
<tr>
<td> 25
</td>
<td> Email Status Tracking (Used to count the number of times an email is opened). This is a special uitype, value for which is computed based on the values of the other table.
</td></tr>
<tr>
<td> 26
</td>
<td> Documents folder
</td></tr>
<tr>
<td> 27
</td>
<td> File type information (Internal/External). This uitype is special as it gives a picklist selection on the left side where label appears and based on which the input type for the value changes.
</td></tr>
<tr>
<td> 28
</td>
<td> Field for filename holder (which was previously merged with another uitype). Now this field exists independent of the other uitype, but its type varies based on the value of the other uitype
</td></tr>
<tr>
<td> 30
</td>
<td> This consists of three dropdowns which are used to set the reminder time in case of any activity creation.
</td></tr>
<tr>
<td> 33
</td>
<td> This is a textarea which behaves like a dropdown combo. The values cannot be edited and the selected value is taken as the input. Does not change colour on selection.
</td></tr>
<tr>
<td> 51
</td>
<td> Used to select an account from a popup window.
</td>
<td>
</td></tr>
<tr>
<td> 52
</td>
<td> A dropdown combo that accepts input from the value selected in it. The input is the name of handler (like admin, standarduser etc.) for the entity being created.
</td>
<td>
</td></tr>
<tr>
<td> 53
</td>
<td> Combination of a dropdown combo and a radiobutton that accepts input from the value selected in the combo. The value of the radiobutton, in turn, decides the values in the combo. The input is the name of the user or group to which an activity is assigned to.
</td></tr>
<tr>
<td> 55
</td>
<td> This uitype provides a combination of Salutation and Firstname. The Salutation field is a dropdown while the Firstname field is a single line textfield which changes its colour on selection.
</td>
<td>
</td></tr>
<tr>
<td> 56
</td>
<td> A checkbox which takes input in the form of yes or no.
</td></tr>
<tr>
<td> 57
</td>
<td> A single line uneditable textfield. Takes its input from the link provided with it. Used to select a contact from a popup window. Contains a link which can be used to clear previous value. Also it contains a link to a popup which adds a new contact. Does not change colour on selection.
</td>
<td>
</td></tr>
<tr>
<td> 255
</td>
<td> In leads and contacts module, last name is mandatory but first name is not. So when first name is disabled for the profile, the salutation gets handled and added for the last name using this uitype.
</td>
<td>
</td></tr>
<tr>
<td> 401
</td>
<td>
<a target="blank" href="page.php?id=<?=node('..UI/colorpicker', $node, 'id')?>">Color</a>
</td>
<td>
</td></tr>
<tr>
<td> 402
</td>
<td>
<a target="blank" href="page.php?id=<?=node('..UI/buttonset', $node, 'id')?>">ButtonSet</a>
</td>
<td>
</td></tr>
</tbody></table>