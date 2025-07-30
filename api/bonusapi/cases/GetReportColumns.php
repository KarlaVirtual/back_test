<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

$ReportName = $_GET["reportName"];

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "success";
$response["ModelErrors"] = [];

if ($ReportName == "PlayerInfo") {
    $response["Data"] = array(
        "RegionId", "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

    );
}
if ($ReportName == "PlayerTables") {
    $response["Data"] = array(
        "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

    );
}
if ($ReportName == "PlayersTable") {
    $response["Data"] = array(
        "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

    );
}

if ($ReportName == "DashboardSettings") {
    $response["Data"] = array(
        "ActivePlayersToday", "NewRegistrationToday", "SportsByStakes", "TopFiveGames", "SportBets", "CasinoBets", "TopFiveMatches", "TopFiveCasinoPlayers",

    );
}
if ($ReportName == "DepositReportSettings") {
    $response["Data"] = array(
        "Id", "ClientId", "CreatedLocal", "TypeName", "CurrencyId", "ModifiedLocal", "PaymentSystemName", "CashDeskId", "State", "ExternalId", "Amount",

    );
}
