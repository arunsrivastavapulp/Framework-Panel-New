$(document).ready(function(){function b(){for(var b=0;b<a.length;b++)"right"==a[b].divposition&&("left"==a[b].position&&$(a[b].location).append('<div class="hint_content"><span class="open_hint">i</span><p>'+a[b].content+"</p></div>"),"right"==a[b].position&&$(a[b].location).append('<div class="hint_content"><span class="open_hint">i</span><p class="left_tip">'+a[b].content+"</p></div>")),"left"==a[b].divposition&&("left"==a[b].position&&$(a[b].location).append('<div class="hint_content left_hint"><span class="open_hint">i</span><p>'+a[b].content+"</p></div>"),"right"==a[b].position&&$(a[b].location).append('<div class="hint_content left_hint"><span class="open_hint">i</span><p class="left_tip">'+a[b].content+"</p></div>"))}var a=[{location:"ul.select-phone li",content:"Welcome to Instappy! Please start by choosing Android or IOS device so that you can see how the app would look like on the Instappy App Preview Simulator",position:"left",divposition:"right"},{location:".pageIndexdiv",content:"This screen is just like a smart phone. It portrays is how your app will look like, acts as a simulator that displays the exact changes that you make on the actual app in real time.",position:"right",divposition:"left"},{location:".GeneralDetails .design_menu_box.Page h2",content:"This is the background of the app's User Interface (UI). It can also be called the background colour of your application.",position:"left",divposition:"left"},{location:".GeneralDetails .design_menu_box.navItemEdit h2",content:"This is the menu that will appear when you either slide from the left corner of the phone towards the right, or simply click on the menu button at the top left corner of the app screen.<br>This menu connects various screens of your app for easier navigation. Though, you can link and add as many screens as you like, it is suggested that you add the ones that are most beneficial for your business. This increases the user-friendliness, and makes it easier for the user to search for more important things on your app. E.g.: You could add a 'Home'? link on the navigation bar, as it's often used, but we recommend you not to add the 'About Us'? link, as the user may not visit this screen repeatedly.",position:"left",divposition:"left"},{location:".feedback:nth-child(4)",content:"This is a great to have feature on the Navigation Menu, as it will help users send feedback to you via e-mail.<br>It&apos;s a great way to hear from your consumers and improve your app, services, and increase engagement. It would also help you in improving your content, and make it closer to what your consumers would want to know about.",position:"left",divposition:"left"},{location:".menu_head li:nth-child(3)",content:"The name that you enter here would reflect in the Navigation Menu. It is strongly suggested that you use the same name for all your screens as you have used for the Title Bar to avoid any confusion for the user.<br>E.g.: If you have named the main screen of your app 'Home'? then name the link on the Navigation Menu 'Home' as well.",position:"left",divposition:"left"},{location:".feedback.report",content:"This is a non-removable part of your app, which allows users to flag objectionable content. If there is any objectionable content on your app, users can report it to the Instappy Team, and we will review it.<br>After review, if we come across any objectionable content, we will instantly notify you with steps on how you can change it in a given time frame so that you can get your app moving without wasting any of your valuable time.",position:"left",divposition:"left"},{location:".choose_card_tip",content:"Choose from a variety of Rich Media Cards to best suit your need. E.g.: If you have a USP to portray, then use the rectangular card so that it covers the screen from left to right to highlight that particular service. Rest can be displayed through the square shaped cards, which cover half of the screen as compared to the rectangular cards.",position:"left",divposition:"left"},{location:".mid_right_box_body .utility_api_cards:nth-child(4)",content:"Is your social networking profile content heavy? Social Feed Cards is a great way to show your consumers the content from your social networking profile without having them leave your mobile app.<br>Social Feed Cards pick the latest post from your profile and display it as is within the app screen. If the user wishes, they can even click on the link and go to your social networking site.",position:"left",divposition:"left"},{location:".mid_right_box_body .utility_api_cards:nth-child(3)",content:"Social Cards offer you the option of letting the user follow the link to your social networking site. But unlike a Rich Media API, it will not display content from your from it.",position:"left",divposition:"left"},{location:".mid_right_box_body .utility_api_cards:nth-child(2)",content:"e.g.: If you wish to have a calendar on your app so that your consumers can plan their week, simply drag the calendar card from below to the preview screen, and place it where you want it to be. And don't forget to link each card with a screen.",position:"left",divposition:"left"},{location:".design_menu_box.videoEdit h2",content:"You can embed a video to your app by copying the link of the video that you would your consumers to view.",position:"left",divposition:"left"},{location:".design_menu_box.bigWidgetTextEdit .content_label:nth-child(9)",content:"You can use this section to give additional information about your product. E.g.: You run one of the coolest restaurants in town, you can use this space to add the price of the dish.",position:"left",divposition:"left"},{location:".design_menu_box.contactWidget.with_tip .content_label:nth-child(5)",content:"You can use this section to give additional information about your product. E.g.: You run one of the coolest restaurants in town, you can use this space to add the price of the dish.",position:"left",divposition:"left"},{location:".design_menu_box.widgetEdit.mapwidgetEdit .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.contactSmallWidget .content_label.head_tip",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.contactImgSmallWidget .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.bigWidgetAboutEdit .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.tabEdit .tabbing_1 h2",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.bigWidgetEdit .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.contact1Widget.with_tip .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.headDiscBigWidget.with_tip .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.headContWidgetEdit .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.contactWidget.with_tip .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.fullWidgetLongText.with_tip .content_label:nth-child(3)",content:"Headline is the category or screen that the card represents. We recommend that you keep headlines short. They are completely customizable as you can change the font size, type, and colour to match it with the app interface.<br>We also strongly suggest you to use easy to read fonts, and avoid using too many colours, as it will dilute the value of the image.",position:"left",divposition:"left"},{location:".design_menu_box.widgetEdit.mapwidgetEdit h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.bigWidgetAboutEdit.cont_tip h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.bigWidgetEdit.cont_tip h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.bigWidgetTextEdit h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.contact1Widget.with_tip h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.headDiscBigWidget.with_tip h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.headContWidgetEdit h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.contactWidget.with_tip h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"},{location:".design_menu_box.fullWidgetLongText.with_tip h2, .design_menu_box.compoNent58 h2",content:"We recommend that you write, and proof check your content before you upload it on to your app. We recommend you to type on a Word Document, and then copy and paste the content over here for ease of use.<br>If you are really good at typing, which we are sure you are, you can even type the content directly into the text area provided below, and use the Text Editor to format your content to make it look more appealing.",position:"left",divposition:"left"}];b(),$(".content_fontsize").change(function(){return $("#present").hasClass("change_text_option")?void $("#present").find(".change_text_area > h2").css("font-size",$(this).val()+"px"):($("#present").find('[class$="_widget_text"]').children("p").css("font-size",$(this).val()+"px"),$("#present").find("a").css("font-size",$(this).val()+"px"),$("#present p,#present h2").css("font-size",$(this).val()+"px"),void $("#present").find("label.common_input_label").css("font-size",$(this).val()+"px"))}),$(".content_fontsize_form").change(function(){$("#present").find("label.common_input_label").css("font-size",$(this).val()+"px")}),$(".content_fontsize1").change(function(){$("#present p.img_heading").css("font-size",$(this).val()+"px")}),$(".content_fontsize2").change(function(){$("#present p.img_content").css("font-size",$(this).val()+"px")}),$(".content_fontsize3").change(function(){$("#present p.text_heading").css("font-size",$(this).val()+"px")}),$(".content_fontsize4").change(function(){$("#present p.text_subheading").css("font-size",$(this).val()+"px")}),$(".content_fontsize5").change(function(){$("#present p.text_content").css("font-size",$(this).val()+"px")}),$(".content_fontsize6").change(function(){$("#present p.about").css("font-size",$(this).val()+"px")}),$(".content_fontsize7").change(function(){$("#present p.item_heading").css("font-size",$(this).val()+"px")}),$(".content_fontsize8").change(function(){$("#present p.item_content").css("font-size",$(this).val()+"px")}),$(".content_fontsize9").change(function(){$("#present p.item_disc").css("font-size",$(this).val()+"px")}),$(".content_fontsize10").change(function(){$("#present p.contactus").css("font-size",$(this).val()+"px")}),$(".content_fontsize14").change(function(){$("#present p.contact_small_subheading").css("font-size",$(this).val()+"px"),$("#present").hasClass("change_text_option")&&($("#present").find(".change_text_area > p").css("font-size",$(this).val()+"px"),$(".content_fontsize14 option:last").remove())}),$(".content_fontsize17").change(function(){$("#present div.long_text_subheading").css("font-size",$(this).val()+"px")}),$(".content_fontsize18").change(function(){$("#present div.long_text_content").css("font-size",$(this).val()+"px"),globalRestart()}),$(".tabcontent_fontsize").change(function(){var a=$(this).parents(".tabbing_1").index(".tabbing_1");$("#present").find(".tab_content").eq(a).find("p").css("font-size",$(this).val()+"px")}),$(".content_fontsize19").change(function(){var a=$(this).parents(".tabbing_1").index(".tabbing_1");$("#present").find(".tabbing_widget_head ul li").eq(a).find("a").css("font-size",$(this).val()+"px")}),$(".content_fontsize20").change(function(){$("#present p.contact_img_heading").css("font-size",$(this).val()+"px")}),$(".content_fontsize21").change(function(){$("#present p.contact_img_subheading").css("font-size",$(this).val()+"px")}),$(".content_fontsize22").change(function(){$("#present .ariba-text-change > h2").css("font-size",$(this).val()+"px")}),$(".content_fontsize23").change(function(){$("#present .ariba-text-change > p").css("font-size",$(this).val()+"px")}),$(".content_fontsize15").change(function(){$("#present .head_disc_content h2").css("font-size",$(this).val()+"px")}),$(".content_font").change(function(){return $("#present").hasClass("change_text_option")?void $("#present").find(".change_text_area > h2").css("font-family",$(this).val()):($("#present").find('[class$="_widget_text"]').children("p:not('.widgetClose')").css("font-family",$(this).val()),$("#present").find("a").css("font-family",$(this).val()),$("#present p:not('.widgetClose')").css("font-family",$(this).val()),$("#present").find("h2.calendar-heading").css("font-family",$(this).val()),$("#present").parents(".festival-card").find("h2.festival-heading").css("font-family",$(this).val()),void $("#present").find("label.common_input_label").css("font-family",$(this).val()))}),$(".content_font_form").change(function(){$("#present").find("label.common_input_label").css("font-family",$(this).val())}),$(".content_font1").change(function(){$("#present p.img_heading").css("font-family",$(this).val())}),$(".content_font2").change(function(){$("#present p.img_content").css("font-family",$(this).val())}),$(".content_font3").change(function(){$("#present p.text_heading").css("font-family",$(this).val())}),$(".content_font4").change(function(){$("#present p.text_subheading").css("font-family",$(this).val())}),$(".content_font5").change(function(){$("#present p.text_content").css("font-family",$(this).val())}),$(".content_font6").change(function(){$("#present p.about").css("font-family",$(this).val())}),$(".content_font7").change(function(){$("#present p.item_heading").css("font-family",$(this).val())}),$(".content_font8").change(function(){$("#present p.item_content").css("font-family",$(this).val())}),$(".content_font9").change(function(){$("#present p.item_disc").css("font-family",$(this).val())}),$(".content_font10").change(function(){$("#present p.contactus").css("font-family",$(this).val())}),$(".content_font13").change(function(){$("#present .contact_small_heading").css("font-family",$(this).val())}),$(".content_font14").change(function(){$("#present p.contact_small_subheading").css("font-family",$(this).val()),$("#present").hasClass("change_text_option")&&$("#present").find(".change_text_area > p").css("font-family",$(this).val())}),$(".content_font15").change(function(){$("#present .head_disc_content h2").css("font-family",$(this).val())}),$(".content_font17").change(function(){$("#present div.long_text_subheading").css("font-family",$(this).val())}),$(".content_font18").change(function(){$("#present div.long_text_content").css("font-family",$(this).val())}),$(".content_font19").change(function(){var a=$(this).parents(".tabbing_1").index(".tabbing_1");$("#present").find(".tabbing_widget_head ul li").eq(a).find("a").css("font-family",$(this).val())}),$(".content_font20").change(function(){$("#present p.contact_img_heading").css("font-family",$(this).val())}),$(".content_font21").change(function(){$("#present p.contact_img_subheading").css("font-family",$(this).val())}),$(".content_font22").change(function(){$("#present .ariba-text-change > h2").css("font-family",$(this).val())}),$(".content_font23").change(function(){$("#present .ariba-text-change > p").css("font-family",$(this).val())}),$(".content_font24").change(function(){$("p.item_heading, p.item_disc").css("font-family",$(this).val())}),$(".tabcontent_font").change(function(){var a=$(this).parents(".tabbing_1").index(".tabbing_1");$("#present").find(".tab_content").eq(a).find("p").css("font-family",$(this).val())}),$(".content_bold16").click(function(){$("#present .head_disc_content p").toggleClass("bold")}),$(".tabcontent_bold").click(function(){var a=$(this).parents(".tabbing_1").index(".tabbing_1");$("#present").find(".tab_content").eq(a).find("p").toggleClass("bold")}),$(".content_italics16").click(function(){$("#present .head_disc_content p").toggleClass("italic")}),$(".tabcontent_italics").click(function(){var a=$(this).parents(".tabbing_1").index(".tabbing_1");$("#present").find(".tab_content").eq(a).find("p").toggleClass("italic")}),$(".content_underline16").click(function(){$("#present .head_disc_content p").toggleClass("underline")}),$(".tabcontent_underline").click(function(){var a=$(this).parents(".tabbing_1").index(".tabbing_1");$("#present").find(".tab_content").eq(a).find("p").toggleClass("underline")}),$(".actionBarPicker").colpick({colorScheme:"dark",layout:"rgbhex",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$(".theme_head").css("background-color","#"+b),$(".mobile nav").css("background-color","#"+b),$("[class*='widget'] span[class^='icon'], .contact_card span[class^='icon']").css("color","#"+b)}}),$("#tabHeadBackGroundEdit").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present .tabbing_widget_head ul li a").css("background-color","#"+b)}}),$("#tabContentBackGroundEdit").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").css("background-color","#"+b)}}),$("#pagePicker").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$(".theme_head").attr("data-appBackground","#"+b),$(".container.droparea").css("background-color","#"+b)}}),$("#pagePicker1").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b)}}),$("#actionBarTextPicker").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$(".theme_head").find("h1").css("color","#"+b)}}),$(".contactPicker").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").css("background-color","#"+b)}}),$(".eventPicker").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#eventActive.festival-card").css("background-color","#"+b)}}),$(".editPicker").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").hasClass("change_text_option")&&$("#present").find(".change_text_area > h2").css("color","#"+b),$("#present").hasClass("big_widget")&&$("#present").find(".big_widget_img_text p.img_heading").css("color","#"+b),$("#present").find('[class$="_widget_text"]').children("p:not('.widgetClose')").css("color","#"+b),$("#present").find("a").css("color","#"+b),$("#present").find(".change_text_area")||$("#present p").css("color","#"+b),$("#present").hasClass(".social_icons")||$("p.next").css("color","#"+b),$("#present").hasClass("contact_card")&&$("#present").find(".contact_card_text > p").css("color","#"+b),$("#present").hasClass("custom_widget")&&$("#present").find(".small_widget_edit_text > p").css("color","#"+b),$("#present").find("h2.calendar-heading").css("color","#"+b),$("#present").parents(".festival-card").find("h2.festival-heading").css("color","#"+b)}}),$(".editPicker1").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present").find("a").css("color","#"+b),$("#present p.img_heading").css("color","#"+b)}}),$(".editPicker11").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.contactus").css("color","#"+b)}}),$(".editPickerfortabBackground").colpick({colorScheme:"dark",layout:"rgbhex",color:"fab23d",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find(".big_widget_bottom_text").css("background-color","#"+b)}}),$(".editPicker2").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present").find("a").css("color","#"+b),$("#present p.text_heading").css("color","#"+b)}}),$(".editPicker3").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present").find("a").css("color","#"+b),$("#present p.text_subheading").css("color","#"+b)}}),$(".editPicker4").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present").find("a").css("color","#"+b),$("#present p.text_content").css("color","#"+b)}}),$(".editPicker5").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present").find("a").css("color","#"+b),$("#present p.about").css("color","#"+b)}}),$(".editPicker6").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.img_heading").css("color","#"+b)}}),$(".editPicker7").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.img_content").css("color","#"+b)}}),$(".editPicker8").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.item_heading").css("color","#"+b)}}),$(".editPicker9").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.item_content").css("color","#"+b)}}),$(".editPicker10").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.item_disc").css("color","#"+b)}}),$(".editPicker14").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.contact_small_heading").css("color","#"+b)}}),$(".editPicker15").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").hasClass("change_text_option")&&$("#present").find(".change_text_area > p").css("color","#"+b),$("#present").find('[class$="_widget_text"]').children("p").css("color","#"+b),$("#present p.contact_small_subheading").css("color","#"+b)}}),$(".editPicker16").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present").find(".head_disc_content").children("h2").css("color","#"+b)}}),$(".editPicker18").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present p.img_heading").css("color","#"+b)}}),$(".editPicker19").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present div.long_text_subheading").css("color","#"+b)}}),$(".editPicker20").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present div.long_text_content").css("color","#"+b)}}),$(".editPicker21").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){var f=$(this).parents(".tabbing_1").index(".tabbing_1");$(d).css("background-color","#"+b),$("#present .tabbing_widget_head ul li").eq(f).find("a").css("color","#"+b)}}),$(".editPicker22").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(this).parents(".tabbing_1").index(".tabbing_1");$(d).css("background-color","#"+b),$("#present p.contact_img_heading").css("color","#"+b)}}),$(".editPicker23").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",
submit:0,onChange:function(a,b,c,d,e){$(this).parents(".tabbing_1").index(".tabbing_1");$(d).css("background-color","#"+b),$("#present p.contact_img_subheading").css("color","#"+b)}}),$(".editPicker24").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present .half_widget_text").css("background-color","#"+b)}}),$(".editPicker25").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present .full_widget_text").css("background-color","#"+b)}}),$(".editPicker26").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present.language").css("background-color","#"+b)}}),$(".editPicker27").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present .ariba-text-change > h2").css("color","#"+b)}}),$(".editPicker28").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("#present .ariba-text-change > p").css("color","#"+b)}}),$(".editPicker29").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(d).css("background-color","#"+b),$("p.item_heading, p.item_disc").css("color","#"+b)}}),$(".tabeditPicker").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){var f=$(this).parents(".tabbing_1").index(".tabbing_1");$(d).css("background-color","#"+b),$("#present").find(".tab_content").eq(f).find("p").css("color","#"+b)}}),$(".colorselect_box").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(this).parents(".tabbing_1").index(".tabbing_1");$(d).css("background-color","#"+b),$("#present").find("input:text").css("background-color","#"+b),$("#present").find("textarea").css("background-color","#"+b),$("#present").find("select").css("background-color","#"+b),$("#present").find("label.common_input_label").css("color","#"+b)}}),$(".colorselect_box_form").colpick({colorScheme:"dark",layout:"rgbhex",color:"ff8800",submit:0,onChange:function(a,b,c,d,e){$(this).parents(".tabbing_1").index(".tabbing_1");$(d).css("background-color","#"+b),$("#present").find("label.common_input_label").css("color","#"+b)}})});