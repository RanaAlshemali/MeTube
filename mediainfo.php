<? error_reporting(0);
ini_set('display_errors', 0); ?>
<?php

// Global variable for table object
$media = NULL;

//
// Table class for media
//
class cmedia extends cTable {
	var $filename;
	var $username;
	var $type;
	var $mediaid;
	var $path;
	var $dateCreated;
	var $description;
	var $keywords;
	var $duration;
	var $privacy;
	var $catagory;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'media';
		$this->TableName = 'media';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`media`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);
		$this->BasicSearch->TypeDefault = "OR";

		// filename
		$this->filename = new cField('media', 'media', 'x_filename', 'filename', '`filename`', '`filename`', 200, -1, FALSE, '`filename`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['filename'] = &$this->filename;

		// username
		$this->username = new cField('media', 'media', 'x_username', 'username', '`username`', '`username`', 200, -1, FALSE, '`username`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['username'] = &$this->username;

		// type
		$this->type = new cField('media', 'media', 'x_type', 'type', '`type`', '`type`', 200, -1, FALSE, '`type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['type'] = &$this->type;

		// mediaid
		$this->mediaid = new cField('media', 'media', 'x_mediaid', 'mediaid', '`mediaid`', '`mediaid`', 3, -1, FALSE, '`mediaid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->mediaid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['mediaid'] = &$this->mediaid;

		// path
		$this->path = new cField('media', 'media', 'x_path', 'path', '`path`', '`path`', 200, -1, FALSE, '`path`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['path'] = &$this->path;

		// dateCreated
		$this->dateCreated = new cField('media', 'media', 'x_dateCreated', 'dateCreated', '`dateCreated`', 'DATE_FORMAT(`dateCreated`, \'%Y/%m/%d\')', 135, 5, FALSE, '`dateCreated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dateCreated->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['dateCreated'] = &$this->dateCreated;

		// description
		$this->description = new cField('media', 'media', 'x_description', 'description', '`description`', '`description`', 200, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['description'] = &$this->description;

		// keywords
		$this->keywords = new cField('media', 'media', 'x_keywords', 'keywords', '`keywords`', '`keywords`', 200, -1, FALSE, '`keywords`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['keywords'] = &$this->keywords;

		// duration
		$this->duration = new cField('media', 'media', 'x_duration', 'duration', '`duration`', '`duration`', 200, -1, FALSE, '`duration`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['duration'] = &$this->duration;

		// privacy
		$this->privacy = new cField('media', 'media', 'x_privacy', 'privacy', '`privacy`', '`privacy`', 200, -1, FALSE, '`privacy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['privacy'] = &$this->privacy;

		// catagory
		$this->catagory = new cField('media', 'media', 'x_catagory', 'catagory', '`catagory`', '`catagory`', 200, -1, FALSE, '`catagory`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['catagory'] = &$this->catagory;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`media`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('mediaid', $rs))
				ew_AddFilter($where, ew_QuotedName('mediaid', $this->DBID) . '=' . ew_QuotedValue($rs['mediaid'], $this->mediaid->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`mediaid` = @mediaid@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->mediaid->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@mediaid@", ew_AdjustSql($this->mediaid->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "medialist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "medialist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("mediaview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("mediaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "mediaadd.php?" . $this->UrlParm($parm);
		else
			$url = "mediaadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("mediaedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("mediaadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("mediadelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "mediaid:" . ew_VarToJson($this->mediaid->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->mediaid->CurrentValue)) {
			$sUrl .= "mediaid=" . urlencode($this->mediaid->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["mediaid"]))
				$arKeys[] = ew_StripSlashes($_POST["mediaid"]);
			elseif (isset($_GET["mediaid"]))
				$arKeys[] = ew_StripSlashes($_GET["mediaid"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->mediaid->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->filename->setDbValue($rs->fields('filename'));
		$this->username->setDbValue($rs->fields('username'));
		$this->type->setDbValue($rs->fields('type'));
		$this->mediaid->setDbValue($rs->fields('mediaid'));
		$this->path->setDbValue($rs->fields('path'));
		$this->dateCreated->setDbValue($rs->fields('dateCreated'));
		$this->description->setDbValue($rs->fields('description'));
		$this->keywords->setDbValue($rs->fields('keywords'));
		$this->duration->setDbValue($rs->fields('duration'));
		$this->privacy->setDbValue($rs->fields('privacy'));
		$this->catagory->setDbValue($rs->fields('catagory'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// filename
		// username
		// type
		// mediaid
		// path
		// dateCreated
		// description
		// keywords
		// duration
		// privacy
		// catagory
		// filename

		$this->filename->ViewValue = $this->filename->CurrentValue;
		$this->filename->ViewCustomAttributes = "";

		// username
		$this->username->ViewValue = $this->username->CurrentValue;
		$this->username->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// mediaid
		$this->mediaid->ViewValue = $this->mediaid->CurrentValue;
		$this->mediaid->ViewCustomAttributes = "";

		// path
		$this->path->ViewValue = $this->path->CurrentValue;
		$this->path->ViewCustomAttributes = "";

		// dateCreated
		$this->dateCreated->ViewValue = $this->dateCreated->CurrentValue;
		$this->dateCreated->ViewValue = ew_FormatDateTime($this->dateCreated->ViewValue, 5);
		$this->dateCreated->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// keywords
		$this->keywords->ViewValue = $this->keywords->CurrentValue;
		$this->keywords->ViewCustomAttributes = "";

		// duration
		$this->duration->ViewValue = $this->duration->CurrentValue;
		$this->duration->ViewCustomAttributes = "";

		// privacy
		$this->privacy->ViewValue = $this->privacy->CurrentValue;
		$this->privacy->ViewCustomAttributes = "";

		// catagory
		$this->catagory->ViewValue = $this->catagory->CurrentValue;
		$this->catagory->ViewCustomAttributes = "";

		// filename
		$this->filename->LinkCustomAttributes = "";
		$this->filename->HrefValue = "";
		$this->filename->TooltipValue = "";

		// username
		$this->username->LinkCustomAttributes = "";
		$this->username->HrefValue = "";
		$this->username->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// mediaid
		$this->mediaid->LinkCustomAttributes = "";
		$this->mediaid->HrefValue = "";
		$this->mediaid->TooltipValue = "";

		// path
		$this->path->LinkCustomAttributes = "";
		$this->path->HrefValue = "";
		$this->path->TooltipValue = "";

		// dateCreated
		$this->dateCreated->LinkCustomAttributes = "";
		$this->dateCreated->HrefValue = "";
		$this->dateCreated->TooltipValue = "";

		// description
		$this->description->LinkCustomAttributes = "";
		$this->description->HrefValue = "";
		$this->description->TooltipValue = "";

		// keywords
		$this->keywords->LinkCustomAttributes = "";
		$this->keywords->HrefValue = "";
		$this->keywords->TooltipValue = "";

		// duration
		$this->duration->LinkCustomAttributes = "";
		$this->duration->HrefValue = "";
		$this->duration->TooltipValue = "";

		// privacy
		$this->privacy->LinkCustomAttributes = "";
		$this->privacy->HrefValue = "";
		$this->privacy->TooltipValue = "";

		// catagory
		$this->catagory->LinkCustomAttributes = "";
		$this->catagory->HrefValue = "";
		$this->catagory->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// filename
		$this->filename->EditAttrs["class"] = "form-control";
		$this->filename->EditCustomAttributes = "";
		$this->filename->EditValue = $this->filename->CurrentValue;
		$this->filename->PlaceHolder = ew_RemoveHtml($this->filename->FldCaption());

		// username
		$this->username->EditAttrs["class"] = "form-control";
		$this->username->EditCustomAttributes = "";
		$this->username->EditValue = $this->username->CurrentValue;
		$this->username->PlaceHolder = ew_RemoveHtml($this->username->FldCaption());

		// type
		$this->type->EditAttrs["class"] = "form-control";
		$this->type->EditCustomAttributes = "";
		$this->type->EditValue = $this->type->CurrentValue;
		$this->type->PlaceHolder = ew_RemoveHtml($this->type->FldCaption());

		// mediaid
		$this->mediaid->EditAttrs["class"] = "form-control";
		$this->mediaid->EditCustomAttributes = "";
		$this->mediaid->EditValue = $this->mediaid->CurrentValue;
		$this->mediaid->ViewCustomAttributes = "";

		// path
		$this->path->EditAttrs["class"] = "form-control";
		$this->path->EditCustomAttributes = "";
		$this->path->EditValue = $this->path->CurrentValue;
		$this->path->PlaceHolder = ew_RemoveHtml($this->path->FldCaption());

		// dateCreated
		$this->dateCreated->EditAttrs["class"] = "form-control";
		$this->dateCreated->EditCustomAttributes = "";
		$this->dateCreated->EditValue = ew_FormatDateTime($this->dateCreated->CurrentValue, 5);
		$this->dateCreated->PlaceHolder = ew_RemoveHtml($this->dateCreated->FldCaption());

		// description
		$this->description->EditAttrs["class"] = "form-control";
		$this->description->EditCustomAttributes = "";
		$this->description->EditValue = $this->description->CurrentValue;
		$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

		// keywords
		$this->keywords->EditAttrs["class"] = "form-control";
		$this->keywords->EditCustomAttributes = "";
		$this->keywords->EditValue = $this->keywords->CurrentValue;
		$this->keywords->PlaceHolder = ew_RemoveHtml($this->keywords->FldCaption());

		// duration
		$this->duration->EditAttrs["class"] = "form-control";
		$this->duration->EditCustomAttributes = "";
		$this->duration->EditValue = $this->duration->CurrentValue;
		$this->duration->PlaceHolder = ew_RemoveHtml($this->duration->FldCaption());

		// privacy
		$this->privacy->EditAttrs["class"] = "form-control";
		$this->privacy->EditCustomAttributes = "";
		$this->privacy->EditValue = $this->privacy->CurrentValue;
		$this->privacy->PlaceHolder = ew_RemoveHtml($this->privacy->FldCaption());

		// catagory
		$this->catagory->EditAttrs["class"] = "form-control";
		$this->catagory->EditCustomAttributes = "";
		$this->catagory->EditValue = $this->catagory->CurrentValue;
		$this->catagory->PlaceHolder = ew_RemoveHtml($this->catagory->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->filename->Exportable) $Doc->ExportCaption($this->filename);
					if ($this->username->Exportable) $Doc->ExportCaption($this->username);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->mediaid->Exportable) $Doc->ExportCaption($this->mediaid);
					if ($this->path->Exportable) $Doc->ExportCaption($this->path);
					if ($this->dateCreated->Exportable) $Doc->ExportCaption($this->dateCreated);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
					if ($this->keywords->Exportable) $Doc->ExportCaption($this->keywords);
					if ($this->duration->Exportable) $Doc->ExportCaption($this->duration);
					if ($this->privacy->Exportable) $Doc->ExportCaption($this->privacy);
					if ($this->catagory->Exportable) $Doc->ExportCaption($this->catagory);
				} else {
					if ($this->filename->Exportable) $Doc->ExportCaption($this->filename);
					if ($this->username->Exportable) $Doc->ExportCaption($this->username);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->mediaid->Exportable) $Doc->ExportCaption($this->mediaid);
					if ($this->path->Exportable) $Doc->ExportCaption($this->path);
					if ($this->dateCreated->Exportable) $Doc->ExportCaption($this->dateCreated);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
					if ($this->keywords->Exportable) $Doc->ExportCaption($this->keywords);
					if ($this->duration->Exportable) $Doc->ExportCaption($this->duration);
					if ($this->privacy->Exportable) $Doc->ExportCaption($this->privacy);
					if ($this->catagory->Exportable) $Doc->ExportCaption($this->catagory);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->filename->Exportable) $Doc->ExportField($this->filename);
						if ($this->username->Exportable) $Doc->ExportField($this->username);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->mediaid->Exportable) $Doc->ExportField($this->mediaid);
						if ($this->path->Exportable) $Doc->ExportField($this->path);
						if ($this->dateCreated->Exportable) $Doc->ExportField($this->dateCreated);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
						if ($this->keywords->Exportable) $Doc->ExportField($this->keywords);
						if ($this->duration->Exportable) $Doc->ExportField($this->duration);
						if ($this->privacy->Exportable) $Doc->ExportField($this->privacy);
						if ($this->catagory->Exportable) $Doc->ExportField($this->catagory);
					} else {
						if ($this->filename->Exportable) $Doc->ExportField($this->filename);
						if ($this->username->Exportable) $Doc->ExportField($this->username);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->mediaid->Exportable) $Doc->ExportField($this->mediaid);
						if ($this->path->Exportable) $Doc->ExportField($this->path);
						if ($this->dateCreated->Exportable) $Doc->ExportField($this->dateCreated);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
						if ($this->keywords->Exportable) $Doc->ExportField($this->keywords);
						if ($this->duration->Exportable) $Doc->ExportField($this->duration);
						if ($this->privacy->Exportable) $Doc->ExportField($this->privacy);
						if ($this->catagory->Exportable) $Doc->ExportField($this->catagory);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
