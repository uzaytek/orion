// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mishoo@infoiasi.ro>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Pazar",
 "Pazartesi",
 "Salı",
 "Çarşamba",
 "Perşembe",
 "Cuma",
 "Cumartesi",
 "Pazar");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Pz",
 "Ptesi",
 "Salı",
 "Çar",
 "Per",
 "Cuma",
 "Ctesi",
 "Pz");

// full month names
Calendar._MN = new Array
("Ocak",
 "Şubat",
 "Mart",
 "Nisan",
 "Mayıs",
 "Haziran",
 "Temmuz",
 "Ağustos",
 "Eylül",
 "Ekim",
 "Kasım",
 "Aralık");

// short month names
Calendar._SMN = new Array
("Ocak",
 "Şubat",
 "Mart",
 "Nisan",
 "Mayıs",
 "Haz",
 "Tem",
 "Ağus",
 "Eylül",
 "Ekim",
 "Kasım",
 "Aralık");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Hakkında";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"For latest version visit: http://dynarch.com/mishoo/calendar.epl\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Tarih Seçimi:\n" +
"- \xab, \xbb tuşlarını Yılı seçmek için kullanabilirsiniz\n" +
"- " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " tuşlarını Ayı seçmek için kullanabilirsiniz\n" +
"- Tuşlarla hızlı bir şekilde seçim yapmak için fareyi basılı tutun";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Zaman Seçimi:\n" +
"- Arttırmak için üzerine tıklayın\n" +
"- Shift e basılı tutarak tıklarsanız azaltma işlemi yapılır\n" +
"- Basılı tutarak fareyi sag/sol yönünde sürüklerseniz hızlı bir şekilde seçim yapabilirsiniz.";

Calendar._TT["PREV_YEAR"] = "Önceki Yıl(seçim için fareyi basılı tutun)";
Calendar._TT["PREV_MONTH"] = "Önceki Ay(seçim için fareyi basılı tutun)";
Calendar._TT["GO_TODAY"] = "Bugüne git";
Calendar._TT["NEXT_MONTH"] = "Sonraki Ay(seçim için fareyi basılı tutun)";
Calendar._TT["NEXT_YEAR"] = "Sonraki Yıl(seçim için fareyi basılı tutun)";
Calendar._TT["SEL_DATE"] = " Tarih";
Calendar._TT["DRAG_TO_MOVE"] = "Taşımak için Basılı tutun ve sürükleyin";
Calendar._TT["PART_TODAY"] = " (Bugün)";
Calendar._TT["MON_FIRST"] = "Pazartesi Önce";
Calendar._TT["SUN_FIRST"] = "Pazar Önce";
Calendar._TT["CLOSE"] = "Kapat";
Calendar._TT["TODAY"] = "Bugün";
Calendar._TT["TIME_PART"] = "Tıklayın veya Basılı tutarak sürükleyin.";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%e %B %A ";

Calendar._TT["WK"] = "";
