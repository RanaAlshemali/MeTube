<? error_reporting(0);
ini_set('display_errors', 0); ?>
<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "accountinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$register = NULL; // Initialize page object first

class cregister extends caccount {

	// Page ID
	var $PageID = 'register';

	// Project ID
	var $ProjectID = "{38123081-1D14-4172-B112-FF018854E762}";

	// Page object name
	var $PageObjName = 'register';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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

		// Table object (account)
		if (!isset($GLOBALS["account"]) || get_class($GLOBALS["account"]) == "caccount") {
			$GLOBALS["account"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["account"];
		}
		if (!isset($GLOBALS["account"])) $GLOBALS["account"] = new caccount();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'register', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $FormClassName = "form-horizontal ewForm ewRegisterForm";

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Security, $Language, $gsLanguage, $gsFormError, $objForm;
		global $Breadcrumb;

		// Set up Breadcrumb
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("register", "RegisterPage", $url, "", "", TRUE);
		$bUserExists = FALSE;
		if (@$_POST["a_register"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_register"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add

				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($this->username->CurrentValue, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in account class, accountinfo.php

				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
				if ($rs = $UserTableConn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success

						// Auto login user
						if ($Security->ValidateUser($this->username->CurrentValue, $this->password->FormValue, TRUE)) {

							// Nothing to do
						}
						$this->Page_Terminate("index.php"); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->username->CurrentValue = NULL;
		$this->username->OldValue = $this->username->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->FristName->CurrentValue = NULL;
		$this->FristName->OldValue = $this->FristName->CurrentValue;
		$this->LastName->CurrentValue = NULL;
		$this->LastName->OldValue = $this->LastName->CurrentValue;
		$this->_Email->CurrentValue = NULL;
		$this->_Email->OldValue = $this->_Email->CurrentValue;
		$this->Gender->CurrentValue = NULL;
		$this->Gender->OldValue = $this->Gender->CurrentValue;
		$this->BirthDate->CurrentValue = NULL;
		$this->BirthDate->OldValue = $this->BirthDate->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->username->FldIsDetailKey) {
			$this->username->setFormValue($objForm->GetValue("x_username"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		$this->password->ConfirmValue = $objForm->GetValue("c_password");
		if (!$this->FristName->FldIsDetailKey) {
			$this->FristName->setFormValue($objForm->GetValue("x_FristName"));
		}
		if (!$this->LastName->FldIsDetailKey) {
			$this->LastName->setFormValue($objForm->GetValue("x_LastName"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
		if (!$this->Gender->FldIsDetailKey) {
			$this->Gender->setFormValue($objForm->GetValue("x_Gender"));
		}
		if (!$this->BirthDate->FldIsDetailKey) {
			$this->BirthDate->setFormValue($objForm->GetValue("x_BirthDate"));
			$this->BirthDate->CurrentValue = ew_UnFormatDateTime($this->BirthDate->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->username->CurrentValue = $this->username->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->FristName->CurrentValue = $this->FristName->FormValue;
		$this->LastName->CurrentValue = $this->LastName->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->Gender->CurrentValue = $this->Gender->FormValue;
		$this->BirthDate->CurrentValue = $this->BirthDate->FormValue;
		$this->BirthDate->CurrentValue = ew_UnFormatDateTime($this->BirthDate->CurrentValue, 5);
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
		$this->ID->setDbValue($rs->fields('ID'));
		$this->username->setDbValue($rs->fields('username'));
		$this->password->setDbValue($rs->fields('password'));
		$this->FristName->setDbValue($rs->fields('FristName'));
		$this->LastName->setDbValue($rs->fields('LastName'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->Gender->setDbValue($rs->fields('Gender'));
		$this->BirthDate->setDbValue($rs->fields('BirthDate'));
		$this->permissions->setDbValue($rs->fields('permissions'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->username->DbValue = $row['username'];
		$this->password->DbValue = $row['password'];
		$this->FristName->DbValue = $row['FristName'];
		$this->LastName->DbValue = $row['LastName'];
		$this->_Email->DbValue = $row['Email'];
		$this->Gender->DbValue = $row['Gender'];
		$this->BirthDate->DbValue = $row['BirthDate'];
		$this->permissions->DbValue = $row['permissions'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// username
		// password
		// FristName
		// LastName
		// Email
		// Gender
		// BirthDate
		// permissions

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ID
		if (strval($this->ID->CurrentValue) <> "") {
			$this->ID->ViewValue = $this->ID->OptionCaption($this->ID->CurrentValue);
		} else {
			$this->ID->ViewValue = NULL;
		}
		$this->ID->ViewCustomAttributes = "";

		// username
		$this->username->ViewValue = $this->username->CurrentValue;
		$this->username->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $Language->Phrase("PasswordMask");
		$this->password->ViewCustomAttributes = "";

		// FristName
		$this->FristName->ViewValue = $this->FristName->CurrentValue;
		$this->FristName->ViewCustomAttributes = "";

		// LastName
		$this->LastName->ViewValue = $this->LastName->CurrentValue;
		$this->LastName->ViewCustomAttributes = "";

		// Email
		$this->_Email->ViewValue = $this->_Email->CurrentValue;
		$this->_Email->ViewCustomAttributes = "";

		// Gender
		if (strval($this->Gender->CurrentValue) <> "") {
			$this->Gender->ViewValue = $this->Gender->OptionCaption($this->Gender->CurrentValue);
		} else {
			$this->Gender->ViewValue = NULL;
		}
		$this->Gender->ViewCustomAttributes = "";

		// BirthDate
		$this->BirthDate->ViewValue = $this->BirthDate->CurrentValue;
		$this->BirthDate->ImageAlt = $this->BirthDate->FldAlt();
		$this->BirthDate->ViewValue = ew_FormatDateTime($this->BirthDate->ViewValue, 5);
		$this->BirthDate->ViewCustomAttributes = "";

		// permissions
		if (strval($this->permissions->CurrentValue) <> "") {
			$this->permissions->ViewValue = $this->permissions->OptionCaption($this->permissions->CurrentValue);
		} else {
			$this->permissions->ViewValue = NULL;
		}
		$this->permissions->ViewCustomAttributes = "";

			// username
			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";
			$this->username->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// FristName
			$this->FristName->LinkCustomAttributes = "";
			$this->FristName->HrefValue = "";
			$this->FristName->TooltipValue = "";

			// LastName
			$this->LastName->LinkCustomAttributes = "";
			$this->LastName->HrefValue = "";
			$this->LastName->TooltipValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
			$this->_Email->TooltipValue = "";

			// Gender
			$this->Gender->LinkCustomAttributes = "";
			$this->Gender->HrefValue = "";
			$this->Gender->TooltipValue = "";

			// BirthDate
			$this->BirthDate->LinkCustomAttributes = "";
			$this->BirthDate->HrefValue = "";
			$this->BirthDate->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// username
			$this->username->EditAttrs["class"] = "form-control";
			$this->username->EditCustomAttributes = "";
			$this->username->EditValue = ew_HtmlEncode($this->username->CurrentValue);
			$this->username->PlaceHolder = ew_RemoveHtml($this->username->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// FristName
			$this->FristName->EditAttrs["class"] = "form-control";
			$this->FristName->EditCustomAttributes = "";
			$this->FristName->EditValue = ew_HtmlEncode($this->FristName->CurrentValue);
			$this->FristName->PlaceHolder = ew_RemoveHtml($this->FristName->FldCaption());

			// LastName
			$this->LastName->EditAttrs["class"] = "form-control";
			$this->LastName->EditCustomAttributes = "";
			$this->LastName->EditValue = ew_HtmlEncode($this->LastName->CurrentValue);
			$this->LastName->PlaceHolder = ew_RemoveHtml($this->LastName->FldCaption());

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);
			$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

			// Gender
			$this->Gender->EditAttrs["class"] = "form-control";
			$this->Gender->EditCustomAttributes = "";
			$this->Gender->EditValue = $this->Gender->Options(TRUE);

			// BirthDate
			$this->BirthDate->EditAttrs["class"] = "form-control";
			$this->BirthDate->EditCustomAttributes = "";
			$this->BirthDate->EditValue = ew_HtmlEncode($this->BirthDate->CurrentValue);
			$this->BirthDate->PlaceHolder = ew_RemoveHtml($this->BirthDate->FldCaption());

			// Add refer script
			// username

			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// FristName
			$this->FristName->LinkCustomAttributes = "";
			$this->FristName->HrefValue = "";

			// LastName
			$this->LastName->LinkCustomAttributes = "";
			$this->LastName->HrefValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";

			// Gender
			$this->Gender->LinkCustomAttributes = "";
			$this->Gender->HrefValue = "";

			// BirthDate
			$this->BirthDate->LinkCustomAttributes = "";
			$this->BirthDate->HrefValue = "";
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
		if (!$this->username->FldIsDetailKey && !is_null($this->username->FormValue) && $this->username->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUserName"));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPassword"));
		}
		if ($this->password->ConfirmValue <> $this->password->FormValue) {
			ew_AddMessage($gsFormError, $Language->Phrase("MismatchPassword"));
		}
		if (!ew_CheckEmail($this->_Email->FormValue)) {
			ew_AddMessage($gsFormError, $this->_Email->FldErrMsg());
		}
		if (!$this->BirthDate->FldIsDetailKey && !is_null($this->BirthDate->FormValue) && $this->BirthDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->BirthDate->FldCaption(), $this->BirthDate->ReqErrMsg));
		}
		if (!ew_CheckDate($this->BirthDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->BirthDate->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->ID->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->ID->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->ID->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
		if ($this->username->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(username = '" . ew_AdjustSql($this->username->CurrentValue, $this->DBID) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->username->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->username->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->_Email->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(Email = '" . ew_AdjustSql($this->_Email->CurrentValue, $this->DBID) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->_Email->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->_Email->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// username
		$this->username->SetDbValueDef($rsnew, $this->username->CurrentValue, "", FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, NULL, FALSE);

		// FristName
		$this->FristName->SetDbValueDef($rsnew, $this->FristName->CurrentValue, NULL, FALSE);

		// LastName
		$this->LastName->SetDbValueDef($rsnew, $this->LastName->CurrentValue, NULL, FALSE);

		// Email
		$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, NULL, FALSE);

		// Gender
		$this->Gender->SetDbValueDef($rsnew, $this->Gender->CurrentValue, NULL, FALSE);

		// BirthDate
		$this->BirthDate->SetDbValueDef($rsnew, $this->BirthDate->CurrentValue, ew_CurrentDate(), FALSE);

		// ID
		// Call Row Inserting event

		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['username']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->ID->setDbValue($conn->Insert_ID());
				$rsnew['ID'] = $this->ID->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);

			// Call User Registered event
			$this->User_Registered($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

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

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// User Registered event
	function User_Registered(&$rs) {

	  //echo "User_Registered";
	}

	// User Activated event
	function User_Activated(&$rs) {

	  //echo "User_Activated";
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($register)) $register = new cregister();

// Page init
$register->Page_Init();

// Page main
$register->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$register->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "register";
var CurrentForm = fregister = new ew_Form("fregister", "register");

// Validate form
fregister.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_username");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterUserName"));
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterPassword"));
			if (fobj.c_password.value != fobj.x_password.value)
				return this.OnError(fobj.c_password, ewLanguage.Phrase("MismatchPassword"));
			elm = this.GetElements("x" + infix + "__Email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($account->_Email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_BirthDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $account->BirthDate->FldCaption(), $account->BirthDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_BirthDate");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($account->BirthDate->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fregister.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregister.ValidateRequired = true;
<?php } else { ?>
fregister.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fregister.Lists["x_Gender"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fregister.Lists["x_Gender"].Options = <?php echo json_encode($account->Gender->Options()) ?>;

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
<?php $register->ShowPageHeader(); ?>
<?php
$register->ShowMessage();
?>
<form name="fregister" id="fregister" class="<?php echo $register->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($register->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $register->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="account">
<input type="hidden" name="a_register" id="a_register" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($account->username->Visible) { // username ?>
	<div id="r_username" class="form-group">
		<label id="elh_account_username" for="x_username" class="col-sm-2 control-label ewLabel"><?php echo $account->username->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $account->username->CellAttributes() ?>>
<span id="el_account_username">
<input type="text" data-table="account" data-field="x_username" name="x_username" id="x_username" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($account->username->getPlaceHolder()) ?>" value="<?php echo $account->username->EditValue ?>"<?php echo $account->username->EditAttributes() ?>>
</span>
<?php echo $account->username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($account->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_account_password" for="x_password" class="col-sm-2 control-label ewLabel"><?php echo $account->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $account->password->CellAttributes() ?>>
<span id="el_account_password">
<div class="input-group" id="ig_password">
<input type="password" data-password-generated="pgt_password" data-table="account" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($account->password->getPlaceHolder()) ?>"<?php echo $account->password->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_password" data-password-confirm="c_password" data-password-generated="pgt_password"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_password" style="display: none;"></span>
</span>
<?php echo $account->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($account->password->Visible) { // password ?>
	<div id="r_c_password" class="form-group">
		<label id="elh_c_account_password" for="c_password" class="col-sm-2 control-label ewLabel"><?php echo $Language->Phrase("Confirm") ?> <?php echo $account->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $account->password->CellAttributes() ?>>
<span id="el_c_account_password">
<input type="password" data-field="c_password" name="c_password" id="c_password" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($account->password->getPlaceHolder()) ?>"<?php echo $account->password->EditAttributes() ?>>
</span>
</div></div>
	</div>
<?php } ?>
<?php if ($account->FristName->Visible) { // FristName ?>
	<div id="r_FristName" class="form-group">
		<label id="elh_account_FristName" for="x_FristName" class="col-sm-2 control-label ewLabel"><?php echo $account->FristName->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $account->FristName->CellAttributes() ?>>
<span id="el_account_FristName">
<input type="text" data-table="account" data-field="x_FristName" name="x_FristName" id="x_FristName" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($account->FristName->getPlaceHolder()) ?>" value="<?php echo $account->FristName->EditValue ?>"<?php echo $account->FristName->EditAttributes() ?>>
</span>
<?php echo $account->FristName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($account->LastName->Visible) { // LastName ?>
	<div id="r_LastName" class="form-group">
		<label id="elh_account_LastName" for="x_LastName" class="col-sm-2 control-label ewLabel"><?php echo $account->LastName->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $account->LastName->CellAttributes() ?>>
<span id="el_account_LastName">
<input type="text" data-table="account" data-field="x_LastName" name="x_LastName" id="x_LastName" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($account->LastName->getPlaceHolder()) ?>" value="<?php echo $account->LastName->EditValue ?>"<?php echo $account->LastName->EditAttributes() ?>>
</span>
<?php echo $account->LastName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($account->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_account__Email" for="x__Email" class="col-sm-2 control-label ewLabel"><?php echo $account->_Email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $account->_Email->CellAttributes() ?>>
<span id="el_account__Email">
<input type="text" data-table="account" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($account->_Email->getPlaceHolder()) ?>" value="<?php echo $account->_Email->EditValue ?>"<?php echo $account->_Email->EditAttributes() ?>>
</span>
<?php echo $account->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($account->Gender->Visible) { // Gender ?>
	<div id="r_Gender" class="form-group">
		<label id="elh_account_Gender" for="x_Gender" class="col-sm-2 control-label ewLabel"><?php echo $account->Gender->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $account->Gender->CellAttributes() ?>>
<span id="el_account_Gender">
<select data-table="account" data-field="x_Gender" data-value-separator="<?php echo ew_HtmlEncode(is_array($account->Gender->DisplayValueSeparator) ? json_encode($account->Gender->DisplayValueSeparator) : $account->Gender->DisplayValueSeparator) ?>" id="x_Gender" name="x_Gender"<?php echo $account->Gender->EditAttributes() ?>>
<?php
if (is_array($account->Gender->EditValue)) {
	$arwrk = $account->Gender->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($account->Gender->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $account->Gender->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($account->Gender->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($account->Gender->CurrentValue) ?>" selected><?php echo $account->Gender->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $account->Gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($account->BirthDate->Visible) { // BirthDate ?>
	<div id="r_BirthDate" class="form-group">
		<label id="elh_account_BirthDate" for="x_BirthDate" class="col-sm-2 control-label ewLabel"><?php echo $account->BirthDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $account->BirthDate->CellAttributes() ?>>
<span id="el_account_BirthDate">
<input type="text" data-table="account" data-field="x_BirthDate" data-format="5" name="x_BirthDate" id="x_BirthDate" placeholder="<?php echo ew_HtmlEncode($account->BirthDate->getPlaceHolder()) ?>" value="<?php echo $account->BirthDate->EditValue ?>"<?php echo $account->BirthDate->EditAttributes() ?>>
</span>
<?php echo $account->BirthDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("RegisterBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fregister.Init();
</script>
<?php
$register->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$register->Page_Terminate();
?>
