<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "mediainfo.php" ?>
<?php include_once "accountinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$media_edit = NULL; // Initialize page object first

class cmedia_edit extends cmedia {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{53922017-685C-481E-A152-5F21D353AE7E}";

	// Table name
	var $TableName = 'media';

	// Page object name
	var $PageObjName = 'media_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (media)
		if (!isset($GLOBALS["media"]) || get_class($GLOBALS["media"]) == "cmedia") {
			$GLOBALS["media"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["media"];
		}

		// Table object (account)
		if (!isset($GLOBALS['account'])) $GLOBALS['account'] = new caccount();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'media', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (account)
		if (!isset($UserTable)) {
			$UserTable = new caccount();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("medialist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->mediaid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $media;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($media);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["mediaid"] <> "") {
			$this->mediaid->setQueryStringValue($_GET["mediaid"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->mediaid->CurrentValue == "")
			$this->Page_Terminate("medialist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("medialist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "medialist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->filename->FldIsDetailKey) {
			$this->filename->setFormValue($objForm->GetValue("x_filename"));
		}
		if (!$this->username->FldIsDetailKey) {
			$this->username->setFormValue($objForm->GetValue("x_username"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->mediaid->FldIsDetailKey)
			$this->mediaid->setFormValue($objForm->GetValue("x_mediaid"));
		if (!$this->path->FldIsDetailKey) {
			$this->path->setFormValue($objForm->GetValue("x_path"));
		}
		if (!$this->dateCreated->FldIsDetailKey) {
			$this->dateCreated->setFormValue($objForm->GetValue("x_dateCreated"));
			$this->dateCreated->CurrentValue = ew_UnFormatDateTime($this->dateCreated->CurrentValue, 5);
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->keywords->FldIsDetailKey) {
			$this->keywords->setFormValue($objForm->GetValue("x_keywords"));
		}
		if (!$this->duration->FldIsDetailKey) {
			$this->duration->setFormValue($objForm->GetValue("x_duration"));
		}
		if (!$this->privacy->FldIsDetailKey) {
			$this->privacy->setFormValue($objForm->GetValue("x_privacy"));
		}
		if (!$this->catagory->FldIsDetailKey) {
			$this->catagory->setFormValue($objForm->GetValue("x_catagory"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->filename->CurrentValue = $this->filename->FormValue;
		$this->username->CurrentValue = $this->username->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->mediaid->CurrentValue = $this->mediaid->FormValue;
		$this->path->CurrentValue = $this->path->FormValue;
		$this->dateCreated->CurrentValue = $this->dateCreated->FormValue;
		$this->dateCreated->CurrentValue = ew_UnFormatDateTime($this->dateCreated->CurrentValue, 5);
		$this->description->CurrentValue = $this->description->FormValue;
		$this->keywords->CurrentValue = $this->keywords->FormValue;
		$this->duration->CurrentValue = $this->duration->FormValue;
		$this->privacy->CurrentValue = $this->privacy->FormValue;
		$this->catagory->CurrentValue = $this->catagory->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->filename->DbValue = $row['filename'];
		$this->username->DbValue = $row['username'];
		$this->type->DbValue = $row['type'];
		$this->mediaid->DbValue = $row['mediaid'];
		$this->path->DbValue = $row['path'];
		$this->dateCreated->DbValue = $row['dateCreated'];
		$this->description->DbValue = $row['description'];
		$this->keywords->DbValue = $row['keywords'];
		$this->duration->DbValue = $row['duration'];
		$this->privacy->DbValue = $row['privacy'];
		$this->catagory->DbValue = $row['catagory'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// filename
			$this->filename->EditAttrs["class"] = "form-control";
			$this->filename->EditCustomAttributes = "";
			$this->filename->EditValue = ew_HtmlEncode($this->filename->CurrentValue);
			$this->filename->PlaceHolder = ew_RemoveHtml($this->filename->FldCaption());

			// username
			$this->username->EditAttrs["class"] = "form-control";
			$this->username->EditCustomAttributes = "";
			$this->username->EditValue = ew_HtmlEncode($this->username->CurrentValue);
			$this->username->PlaceHolder = ew_RemoveHtml($this->username->FldCaption());

			// type
			$this->type->EditAttrs["class"] = "form-control";
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);
			$this->type->PlaceHolder = ew_RemoveHtml($this->type->FldCaption());

			// mediaid
			$this->mediaid->EditAttrs["class"] = "form-control";
			$this->mediaid->EditCustomAttributes = "";
			$this->mediaid->EditValue = $this->mediaid->CurrentValue;
			$this->mediaid->ViewCustomAttributes = "";

			// path
			$this->path->EditAttrs["class"] = "form-control";
			$this->path->EditCustomAttributes = "";
			$this->path->EditValue = ew_HtmlEncode($this->path->CurrentValue);
			$this->path->PlaceHolder = ew_RemoveHtml($this->path->FldCaption());

			// dateCreated
			$this->dateCreated->EditAttrs["class"] = "form-control";
			$this->dateCreated->EditCustomAttributes = "";
			$this->dateCreated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dateCreated->CurrentValue, 5));
			$this->dateCreated->PlaceHolder = ew_RemoveHtml($this->dateCreated->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// keywords
			$this->keywords->EditAttrs["class"] = "form-control";
			$this->keywords->EditCustomAttributes = "";
			$this->keywords->EditValue = ew_HtmlEncode($this->keywords->CurrentValue);
			$this->keywords->PlaceHolder = ew_RemoveHtml($this->keywords->FldCaption());

			// duration
			$this->duration->EditAttrs["class"] = "form-control";
			$this->duration->EditCustomAttributes = "";
			$this->duration->EditValue = ew_HtmlEncode($this->duration->CurrentValue);
			$this->duration->PlaceHolder = ew_RemoveHtml($this->duration->FldCaption());

			// privacy
			$this->privacy->EditAttrs["class"] = "form-control";
			$this->privacy->EditCustomAttributes = "";
			$this->privacy->EditValue = ew_HtmlEncode($this->privacy->CurrentValue);
			$this->privacy->PlaceHolder = ew_RemoveHtml($this->privacy->FldCaption());

			// catagory
			$this->catagory->EditAttrs["class"] = "form-control";
			$this->catagory->EditCustomAttributes = "";
			$this->catagory->EditValue = ew_HtmlEncode($this->catagory->CurrentValue);
			$this->catagory->PlaceHolder = ew_RemoveHtml($this->catagory->FldCaption());

			// Edit refer script
			// filename

			$this->filename->LinkCustomAttributes = "";
			$this->filename->HrefValue = "";

			// username
			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";

			// mediaid
			$this->mediaid->LinkCustomAttributes = "";
			$this->mediaid->HrefValue = "";

			// path
			$this->path->LinkCustomAttributes = "";
			$this->path->HrefValue = "";

			// dateCreated
			$this->dateCreated->LinkCustomAttributes = "";
			$this->dateCreated->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// keywords
			$this->keywords->LinkCustomAttributes = "";
			$this->keywords->HrefValue = "";

			// duration
			$this->duration->LinkCustomAttributes = "";
			$this->duration->HrefValue = "";

			// privacy
			$this->privacy->LinkCustomAttributes = "";
			$this->privacy->HrefValue = "";

			// catagory
			$this->catagory->LinkCustomAttributes = "";
			$this->catagory->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckDate($this->dateCreated->FormValue)) {
			ew_AddMessage($gsFormError, $this->dateCreated->FldErrMsg());
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if (!$this->keywords->FldIsDetailKey && !is_null($this->keywords->FormValue) && $this->keywords->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keywords->FldCaption(), $this->keywords->ReqErrMsg));
		}
		if (!$this->duration->FldIsDetailKey && !is_null($this->duration->FormValue) && $this->duration->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->duration->FldCaption(), $this->duration->ReqErrMsg));
		}
		if (!$this->privacy->FldIsDetailKey && !is_null($this->privacy->FormValue) && $this->privacy->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->privacy->FldCaption(), $this->privacy->ReqErrMsg));
		}
		if (!$this->catagory->FldIsDetailKey && !is_null($this->catagory->FormValue) && $this->catagory->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->catagory->FldCaption(), $this->catagory->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// filename
			$this->filename->SetDbValueDef($rsnew, $this->filename->CurrentValue, NULL, $this->filename->ReadOnly);

			// username
			$this->username->SetDbValueDef($rsnew, $this->username->CurrentValue, NULL, $this->username->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, $this->type->ReadOnly);

			// path
			$this->path->SetDbValueDef($rsnew, $this->path->CurrentValue, NULL, $this->path->ReadOnly);

			// dateCreated
			$this->dateCreated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dateCreated->CurrentValue, 5), NULL, $this->dateCreated->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, "", $this->description->ReadOnly);

			// keywords
			$this->keywords->SetDbValueDef($rsnew, $this->keywords->CurrentValue, "", $this->keywords->ReadOnly);

			// duration
			$this->duration->SetDbValueDef($rsnew, $this->duration->CurrentValue, "", $this->duration->ReadOnly);

			// privacy
			$this->privacy->SetDbValueDef($rsnew, $this->privacy->CurrentValue, "", $this->privacy->ReadOnly);

			// catagory
			$this->catagory->SetDbValueDef($rsnew, $this->catagory->CurrentValue, "", $this->catagory->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("medialist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($media_edit)) $media_edit = new cmedia_edit();

// Page init
$media_edit->Page_Init();

// Page main
$media_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$media_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmediaedit = new ew_Form("fmediaedit", "edit");

// Validate form
fmediaedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_dateCreated");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($media->dateCreated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $media->description->FldCaption(), $media->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keywords");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $media->keywords->FldCaption(), $media->keywords->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duration");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $media->duration->FldCaption(), $media->duration->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_privacy");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $media->privacy->FldCaption(), $media->privacy->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_catagory");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $media->catagory->FldCaption(), $media->catagory->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fmediaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmediaedit.ValidateRequired = true;
<?php } else { ?>
fmediaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $media_edit->ShowPageHeader(); ?>
<?php
$media_edit->ShowMessage();
?>
<form name="fmediaedit" id="fmediaedit" class="<?php echo $media_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($media_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $media_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="media">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($media->filename->Visible) { // filename ?>
	<div id="r_filename" class="form-group">
		<label id="elh_media_filename" for="x_filename" class="col-sm-2 control-label ewLabel"><?php echo $media->filename->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $media->filename->CellAttributes() ?>>
<span id="el_media_filename">
<input type="text" data-table="media" data-field="x_filename" name="x_filename" id="x_filename" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($media->filename->getPlaceHolder()) ?>" value="<?php echo $media->filename->EditValue ?>"<?php echo $media->filename->EditAttributes() ?>>
</span>
<?php echo $media->filename->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->username->Visible) { // username ?>
	<div id="r_username" class="form-group">
		<label id="elh_media_username" for="x_username" class="col-sm-2 control-label ewLabel"><?php echo $media->username->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $media->username->CellAttributes() ?>>
<span id="el_media_username">
<input type="text" data-table="media" data-field="x_username" name="x_username" id="x_username" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($media->username->getPlaceHolder()) ?>" value="<?php echo $media->username->EditValue ?>"<?php echo $media->username->EditAttributes() ?>>
</span>
<?php echo $media->username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label id="elh_media_type" for="x_type" class="col-sm-2 control-label ewLabel"><?php echo $media->type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $media->type->CellAttributes() ?>>
<span id="el_media_type">
<input type="text" data-table="media" data-field="x_type" name="x_type" id="x_type" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($media->type->getPlaceHolder()) ?>" value="<?php echo $media->type->EditValue ?>"<?php echo $media->type->EditAttributes() ?>>
</span>
<?php echo $media->type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->mediaid->Visible) { // mediaid ?>
	<div id="r_mediaid" class="form-group">
		<label id="elh_media_mediaid" class="col-sm-2 control-label ewLabel"><?php echo $media->mediaid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $media->mediaid->CellAttributes() ?>>
<span id="el_media_mediaid">
<span<?php echo $media->mediaid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $media->mediaid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="media" data-field="x_mediaid" name="x_mediaid" id="x_mediaid" value="<?php echo ew_HtmlEncode($media->mediaid->CurrentValue) ?>">
<?php echo $media->mediaid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->path->Visible) { // path ?>
	<div id="r_path" class="form-group">
		<label id="elh_media_path" for="x_path" class="col-sm-2 control-label ewLabel"><?php echo $media->path->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $media->path->CellAttributes() ?>>
<span id="el_media_path">
<input type="text" data-table="media" data-field="x_path" name="x_path" id="x_path" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($media->path->getPlaceHolder()) ?>" value="<?php echo $media->path->EditValue ?>"<?php echo $media->path->EditAttributes() ?>>
</span>
<?php echo $media->path->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->dateCreated->Visible) { // dateCreated ?>
	<div id="r_dateCreated" class="form-group">
		<label id="elh_media_dateCreated" for="x_dateCreated" class="col-sm-2 control-label ewLabel"><?php echo $media->dateCreated->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $media->dateCreated->CellAttributes() ?>>
<span id="el_media_dateCreated">
<input type="text" data-table="media" data-field="x_dateCreated" data-format="5" name="x_dateCreated" id="x_dateCreated" placeholder="<?php echo ew_HtmlEncode($media->dateCreated->getPlaceHolder()) ?>" value="<?php echo $media->dateCreated->EditValue ?>"<?php echo $media->dateCreated->EditAttributes() ?>>
</span>
<?php echo $media->dateCreated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_media_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $media->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $media->description->CellAttributes() ?>>
<span id="el_media_description">
<input type="text" data-table="media" data-field="x_description" name="x_description" id="x_description" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($media->description->getPlaceHolder()) ?>" value="<?php echo $media->description->EditValue ?>"<?php echo $media->description->EditAttributes() ?>>
</span>
<?php echo $media->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->keywords->Visible) { // keywords ?>
	<div id="r_keywords" class="form-group">
		<label id="elh_media_keywords" for="x_keywords" class="col-sm-2 control-label ewLabel"><?php echo $media->keywords->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $media->keywords->CellAttributes() ?>>
<span id="el_media_keywords">
<input type="text" data-table="media" data-field="x_keywords" name="x_keywords" id="x_keywords" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($media->keywords->getPlaceHolder()) ?>" value="<?php echo $media->keywords->EditValue ?>"<?php echo $media->keywords->EditAttributes() ?>>
</span>
<?php echo $media->keywords->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->duration->Visible) { // duration ?>
	<div id="r_duration" class="form-group">
		<label id="elh_media_duration" for="x_duration" class="col-sm-2 control-label ewLabel"><?php echo $media->duration->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $media->duration->CellAttributes() ?>>
<span id="el_media_duration">
<input type="text" data-table="media" data-field="x_duration" name="x_duration" id="x_duration" size="30" maxlength="5" placeholder="<?php echo ew_HtmlEncode($media->duration->getPlaceHolder()) ?>" value="<?php echo $media->duration->EditValue ?>"<?php echo $media->duration->EditAttributes() ?>>
</span>
<?php echo $media->duration->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->privacy->Visible) { // privacy ?>
	<div id="r_privacy" class="form-group">
		<label id="elh_media_privacy" for="x_privacy" class="col-sm-2 control-label ewLabel"><?php echo $media->privacy->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $media->privacy->CellAttributes() ?>>
<span id="el_media_privacy">
<input type="text" data-table="media" data-field="x_privacy" name="x_privacy" id="x_privacy" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($media->privacy->getPlaceHolder()) ?>" value="<?php echo $media->privacy->EditValue ?>"<?php echo $media->privacy->EditAttributes() ?>>
</span>
<?php echo $media->privacy->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($media->catagory->Visible) { // catagory ?>
	<div id="r_catagory" class="form-group">
		<label id="elh_media_catagory" for="x_catagory" class="col-sm-2 control-label ewLabel"><?php echo $media->catagory->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $media->catagory->CellAttributes() ?>>
<span id="el_media_catagory">
<input type="text" data-table="media" data-field="x_catagory" name="x_catagory" id="x_catagory" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($media->catagory->getPlaceHolder()) ?>" value="<?php echo $media->catagory->EditValue ?>"<?php echo $media->catagory->EditAttributes() ?>>
</span>
<?php echo $media->catagory->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $media_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fmediaedit.Init();
</script>
<?php
$media_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$media_edit->Page_Terminate();
?>
