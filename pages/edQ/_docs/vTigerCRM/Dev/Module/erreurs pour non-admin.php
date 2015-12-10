<pre><code>

INSERT INTO `vtiger_def_org_share`(`tabid`, `permission`, `editstatus`) 
SELECT tabid, 2,0
FROM vtiger_tab WHERE tabid
NOT IN (SELECT tabid FROM vtiger_def_org_share)

</code></pre>