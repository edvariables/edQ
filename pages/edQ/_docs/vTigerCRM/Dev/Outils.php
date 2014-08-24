DELETE FROM `vtiger_contactaddress` WHERE `contactaddressid` IN
(SELECT `contactid` FROM `vtiger_contactscf` WHERE NOT cf_727 IS NULL AND cf_727 <> 0);

DELETE FROM `vtiger_contactdetails` WHERE `contactid` NOT IN
(SELECT `contactaddressid` FROM `vtiger_contactaddress`);