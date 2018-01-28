var cfg = new Array();

cfg["path"] = "http://localhost/sklep";
// Konfiguracja javascript
cfg["INDEX_ADDRESS"] = cfg["path"] + "/";
cfg["ACCOUNT_ADDRESS"] = cfg["path"] + "/account/info";
cfg["SEARCH_URI_PATTERN"] = cfg["path"] + "/products/search/$$page/$$category/$$string";
cfg["SEARCH_NEWORDERS_URI_PATTERN"] = cfg["path"] + "/admin/newOrders/$$page/$$sort";
cfg["SEARCH_OLDORDERS_URI_PATTERN"] = cfg["path"] + "/admin/oldOrders/$$page/$$sort";
cfg["BASKET_UPDATE_URI_PATTERN"] = cfg["path"] + "/basket/update/$$id/$$amount";
cfg["RETURNS_TIME"] = 1000;

// Inicjacja obiektu aplikacji
var PHARMACY_SHOW = {};