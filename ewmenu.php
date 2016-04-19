<? error_reporting(0);
ini_set('display_errors', 0); ?>
<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(12, "mi_PMs", $Language->MenuPhrase("12", "MenuText"), "PMslist.php", -1, "", IsLoggedIn() || AllowListMenu('{38123081-1D14-4172-B112-FF018854E762}PMs'), FALSE);
$RootMenu->AddMenuItem(13, "mi_blockList", $Language->MenuPhrase("13", "MenuText"), "blockListlist.php", -1, "", IsLoggedIn() || AllowListMenu('{38123081-1D14-4172-B112-FF018854E762}blockList'), FALSE);
$RootMenu->AddMenuItem(11, "mi_View_Media_php", $Language->MenuPhrase("11", "MenuText"), "View Media.php", -1, "", IsLoggedIn() || AllowListMenu('{38123081-1D14-4172-B112-FF018854E762}View Media.php'), FALSE);
$RootMenu->AddMenuItem(1, "mi_account", $Language->MenuPhrase("1", "MenuText"), "accountlist.php", -1, "", IsLoggedIn() || AllowListMenu('{38123081-1D14-4172-B112-FF018854E762}account'), FALSE);
$RootMenu->AddMenuItem(10, "mi_Update_Profile_php", $Language->MenuPhrase("10", "MenuText"), "Update Profile.php", -1, "", IsLoggedIn() || AllowListMenu('{38123081-1D14-4172-B112-FF018854E762}Update Profile.php'), FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
