=== WebinarPress ===
Contributors: getwebinarpress.com
Donate link: http://getwebinarpress.com
Tags: webinar, webinars, webseminar, teleseminar, webinarpress, stream, streaming, audio, seminar, video, chat, livechat, WP WebinarSystem, webinarsystem, conference, broadcasting, meeting,
Requires at least: 4.4.2
Tested up to: 5.4.0
Stable tag: 2.24.24
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

(Formerly WP WebinarSystem) WebinarPress allows you to run webinars within your Wordpress website, and customize everything around it.

== Description ==

(Formerly WP WebinarSystem) WebinarPress allows you to run webinars within your Wordpress website, and customize everything around it.

With WebinarPress you can organize live and automated webinars from within your Wordpress website, without any technical skills. 
You can use the powerful livestream future of Google Hangouts on air, or any other prerecorded video from youtube, Vimeo or MP4 file. 
You can interact with your attendees by letting them ask questions or raise their hands.
All the webinarpages are responsive, so visitors can attend a webinar from every mobile device or tablet. 
You can collect a visitors credentials and export it as a csv or excel file. 
WebinarPress also sends reminder emails if you want it to, which you can configure yourself.


For documentation please go to our website: [getwebinarpress.com/documentation](http://getwebinarpress.com/documentation/?utm_source=wpplugindirectory&utm_medium=descriptionpage&utm_campaign=wpplugindirectory)


== Installation ==

1. Upload the entire unzipped folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress admin panel.

You will find the 'WebinarPress' menu in your WordPress admin panel.

For basic usage, you can also have a look at the [website](http://getwebinarpress.com).

== Frequently Asked Questions ==

* Please find our FAQ section on the [website of WebinarPress](http://getwebinarpress.com).
* You can find the documentation on our [Documentation page](http://getwebinarpress.com/documentation).

== Changelog ==

= Version 2.24.24- 2020-05-07 =
- Fix bug introduced in 2.24.23 when displaying session times

= Version 2.24.23- 2020-05-06 =
- Add option to automatically register attendees in active recurring sessions (not just next session)

= Version 2.24.22- 2020-05-05 =
- Add Turkish date locale to registration page and registration widgets
- Login registered attendees instead of re-registering if 'Auto register WP users' is enabled

= Version 2.24.21 - 2020-05-01 =
- Fix export when attendee does not have any custom fields

= Version 2.24.20 - 2020-04-30 =
- Add 'live' webinar sources to automated webinars
- Fix compatibility issue with DZS Video Gallery Plugin
- Fix language loading issue

= Version 2.24.19 - 2020-04-29 =
- Change background image mode in registration header to stop repeating
- Fix issue with Newsletter plugin integration

= Version 2.24.18 - 2020-04-27 =
- Fix custom field saving
- Fix sending questions to host if WebinarPress Live is enabled
- Add check for invalid webinar source type

= Version 2.24.17 - 2020-04-24 =
- Improve email unsubscribes
- Fix Twitch display on mobile devices

= Version 2.24.16 - 2020-04-23 =
- Handle Chinese characters in custom fields
- Allow override for webinar 'From' and support emails
- Fix Chinese characters in custom fields
- Fix sending confirmation email to paid customers when importing attendees

= Version 2.24.15 - 2020-04-21 =
- Added Jitsi support
- Fix MailPoet3 list selection
- Fix issue with iPad on iOS > 13
- Fix WP 4.9 compatibility issue

= Version 2.24.14 - 2020-04-17 =
- Show recurring webinar session date/time in WooCommerce checkout and confirmation pages + emails.
- Add auth to webinar login link in WooCommerce account page

= Version 2.24.13 - 2020-04-16 =
- Fix XSS issue in Safari when embedding scripts like JWPlayer

= Version 2.24.12 - 2020-04-16 =
- Fix issue with Zoom when WebinarPress Connect is disabled
- Improve HTML embedded video source

= Version 2.24.11 - 2020-04-15 =
- Correctly calculate webinar start time in simulated webinar content
- Fix AutoPlay on mobile devices

= Version 2.24.10 - 2020-04-14 =
- Fix issue with required custom fields on paid webinars
- Update MediaElement to 4.2.16
- Handle TinyMCE not being available in webinar editor
- Fix issue in email reminder webinar time
- Fix confirmation page editor date/time display

= Version 2.24.9 - 2020-04-09 =
- Make email imports more intelligent figuring out which column is email and which is name 
- Fix GetResponse integration issue
- Fix & display issue on webinar page

= Version 2.24.8 - 2020-04-07 =
- Add JWPlayer support

= Version 2.24.7 - 2020-04-02 =
- Improve video controls
- Allow custom field order on registration forms
- Added alternate sessions option to form block in registration forms
- Added option to set WordPress featured image
- Support double optin emails for webinar registrations
- Support multiple WooCommerce Membership levels per webinar

= Version 2.24.6 - 2020-03-31 =
- Fix date/time formatting in emails when English is not selected

= Version 2.24.5 - 2020-03-31 =
- Improve parsing of Zoom url

= Version 2.24.4 - 2020-03-30 =
- Disable login on register page if registration is disabled for the webinar
- Zoom enhancements

= Version 2.24.3 - 2020-03-30 =
- Fix uploading large attendee CSVs

= Version 2.24.2 - 2020-03-30 =
- Zoom support
- Limit number of webinar seats available
- Added autostart option on mp4 video source
- Added html placeholders for number of seats left, i.e. {{#placesLeft}}Hurry, only {{places}} places left! {{/placesLeft}} {{#noPlaces}}Sorry there are no places left!{{/noPlaces}}
- Added date/time format settings to alternate sessions on registration page
- Added optional logo to the webinar page
- Fix display issue of &amp; on countdown page
- Fix date/time display issue in WooCommerce for recurring webinars

= Version 2.24.1 - 2020-03-25 =
- WebinarPress Live - Hosted live webinars without leaving WebinarPress!

= Version 2.23.12 - 2020-03-24 =
- Add confirmed status to The Newsletter Plugin subscribers

= Version 2.23.11 - 2020-03-24 =
- Twitch live streaming support
- Mixer live streaming support
- Add MailerLite support
- Add The Newsletter Plugin support
- Add option to control the number of sessions shown on the registration page
- Remove WebinarPress logo from footer

= Version 2.23.10 - 2020-03-24 =
- Compatibility with MySQL 5.5.x

= Version 2.23.9 - 2020-03-20 =
- Fix error on WooCommerce account page if the webinar has been deleted

= Version 2.23.8 - 2020-03-20 =
- Fix issues with session selector on register page

= Version 2.23.7 - 2020-03-16 =
- Improve registration page designer

= Version 2.23.6 - 2020-03-12 =
- Add option to redirect to a URL after the webinar has finished
- Fix issue adding multiple items to the cart in WooCommerce
- Fix issue with shortcode formatting when WPML is enabled

= Version 2.23.5 - 2020-03-09 =
- Fix issue when registering with Registration Widgets

= Version 2.23.4 - 2020-03-06 =
- Fix formatting issues when rendering HTML blocks

= Version 2.23.3 - 2020-03-05 =
- Load pages using POST to avoid page caching plugins and page caching at the host level
- Add auto-login checking from registration page in case the site cache is blocking server based auto-login
- Fix issues with auto-play on iOS

= Version 2.23.2 - 2020-02-20 =
- Fix issue with videos playing in Studio mode on replay
- Improve Vimeo ID description
- Define DONOTCACHEPAGE to disable WP Super Cache and W3 Total Cache for webinar pages

= Version 2.23.1 - 2020-02-10 =
- Add HTML block to countdown timer
- Automated webinar replays
- Improve flow of live webinars
- Disable clicks on videos when controls are hidden
- Fixed issue when accessing protected webinars when WooCommerce Memberships has been disabled.
- Fixed issue with apostrophes
- Disable cache on webinar pages for LightSpeed Cache
- Add import/export webinar settings
- Improve live webinar flow
- In manual webinars don't start team-member's webinar until the webinar starts
- Allow attendees to finish the webinar when not using 'simulated webinar content'
- Fix issue when duplicating webinars

= Version 2.22.1 - 2019-12-10 =
- Newly designed registration page
- Newly designed confirmation page
- Real-time registration page editor
- Real-time confirmation page editor
- Real-time webinar page editor
- Real-time countdown page editor
- Allow translation of all text on the webinar page
- Auto-login attendees when they click on the webinar email link
- Load fonts locally instead of from Google

= Version 2.21.13 - 2019-12-19 =
- Optimize webinar notification emails

= Version 2.21.12 - 2019-12-18 =
- Add beta option to WebinarPress settings to update via the beta channel

= Version 2.21.11 - 2019-12-12 =
- Fix bug when generating links in the webinar editor when using 'Plain' permalinks

= Version 2.21.10 - 2019-12-10 =
- Fix edge case that won't allow attendees to login to webinars if the webinar was changed form recurring to manual

= Version 2.21.9 - 2019-12-09 =
- Fix bug importing CSVs (line ending splitting)

= Version 2.21.8 - 2019-12-04 =
- Allow disabling email notifications when auto-registering an attendee

= Version 2.21.7 - 2019-12-03 =
- Fix issue with multiple logins to paid webinars

= Version 2.21.6 - 2019-11-21 =
- Tweak scrolling on live view for large CTAs

= Version 2.21.5 - 2019-11-21 =
- Allow scrolling on live view for large CTAs

= Version 2.21.4 - 2019-11-20 =
- Added link to WordPress post editor for webinars
- Fixed bug in blue activation bar
- Fixed replay emails

= Version 2.21.3 - 2019-11-18 =
- Fixed issue with the WooCommerce description and thank you message

= Version 2.21.2 - 2019-11-18 =
- Fixed issue duplicating webinars
- Fixed missing webinar date on registration page

= Version 2.21.1 - 2019-11-12 =
- Real-time webinar servers (WebinarPress Connect)
- New attendees page
- New webinar chat log page
- New webinar questions page
- New settings page
- Speed up attendee registration by sending emails in cron
- Webinar specific emails
- Improved email design
- Added Mailrelay platform
- Lots of refactoring and improvements

= Version 2.20.5 - 2019-11-07 =
- Optimize email reminder emails

= Version 2.20.4 - 2019-11-06 =
- Add hosts box to webinar editor
- Optimize email reminder emails
- Improve color picker

= Version 2.20.3 - 2019-11-01 =
- Change unsubscribe action from 'unsubscribe' to 'wpws-unsubscribe'

= Version 2.20.2 - 2019-10-30 =
- Replaced MailChimp API
- General improvements and fixes
- Support for WordPress 5.3
- Added WordPress media selector for images and videos
- Fix issue with registration page content not being saved

= Version 2.20.1 - 2019-10-20 =
- New webinar editor
- Improved live webinar flow
- Replaced countdown page
- Allow WP users to register and login automatically

= Version 2.19.14 - 2019-10-09 =
- Fix issue date/time selector on registration page

= Version 2.19.13 - 2019-10-03 =
- Fix php5.6 compatibility issue with 2.19.12

= Version 2.19.12 - 2019-10-01 =
- Fix timezone calculation issue in Registration widget
- Add date selector to registration page

= Version 2.19.11 - 2019-09-27 =
- Fix additional integration issue with WooCommerce

= Version 2.19.10 - 2019-09-25 =
- Fix integration issue with WooCommerce when non-webinar items are added to the cart
- Fix login issue introduced in 2.19.1

= Version 2.19.9 - 2019-09-19 =
- Fix display issue on live page when an attendee used the chat and was then removed

= Version 2.19.8 - 2019-09-03 =
- Create new WooCommerce product when cloning paid webinars

= Version 2.19.7 - 2019-08-30 =
- Fix small issue with days offset in recurring sessions

= Version 2.19.6 - 2019-08-27 =
- Allow registering for recurring webinars more than a week in the future

= Version 2.19.5 - 2019-08-13 =
- Implement GetResponse API v3

= Version 2.19.4 - 2019-07-25 =
- Fix webinar import that have images

= Version 2.19.3 - 2019-07-18 =
- Fix issue with apostrophe in custom field value

= Version 2.19 - 2019-05-01 =
- New live page layout (studio)
- WordPress actions for attendee signup and joining webinar
- Clickable links on terms in registration widgets
- Show how long attendees were connected for
- Select field in custom fields
- Auto-register attendees via URL
- Automatically add to cart and go to checkout when purchasing paid webinars with a registration widget
- Fix active campaign API verification
- General improvements and fixes

= Version 2.18 - 2019-02-28 =
- Added custom confirmation pages with short codes (calendar, buttons, links, start time, etc)
- Updated to latest version of MediaElement
- Fixed issue deleting chats
- Fixed default logo in emails
- Fixed issue with 1 hour email timer
- Fixed issue with sending emails to attendees when importing a CSV
- General improvements and fixes


= Version 2.17 - 2019-01-25 =
- Change name to WebinarPress
- Added registration widgets
- Added custom script tags to each page for Google Analytics, Facebook etc.
- Added support for WordPresss 5.0
- Add custom translation files
- Updated Italian translation
- Allow customizing of the registration page footer
- Support Wistia embedded video source
- Allow links in GDPR checkbox
- Fix issue with CTA not showing after N minutes
- Fix potential conflict with other plugins
- Fix reserved word issue conflict in MySQL 8.12+
- Fix issue with recurring webinars when used with UK date format
- Fix issue in the shortcode editor when webinar names have quotes
- Fix issue Enormail
- General code improvements and fixes


= Version 2.16 - 2018-10-17 =
- Zapier/Webhooks for registration and attended webinar events
- ConvertKit email integration
- Replaced the color picker
- Add text translations
- Allow customization of the paid webinar link
- Disable thank you page after registration
- Add custom script tags to all webinar pages
- Allow HTML in GDPR text
- Fix issue when re-registering for the same automated webinar
- Updated Spanish translation
- Auto-complete webinar sales when paid with Stripe/PayPal
- General code improvements and fixes


= Version 2.15 - 2018-09-20 =
- Low level optimisation of live view
- Allow WooCommerce thank you page to be customised
- Update german translation
- Fixed position of custom fields in registration shortcode
- General code improvements and fixes


= Version 2.14 - 2018-07-18 =
- Added localization to countdown timer
- Added HLS support
- Added dismiss buttons for MySQL and PHP warnings
- Added auto populate of name and email for logged in users when registering for a webinar
- Fixed admin icon
- Fixed compatibility issue with Wicked Folders Pro
- Fixed issues loading time slots for automated webinars in WooCommerce
- Fixed incorrectly reporting MariaDB as MySQL 5.5.5


= Version 2.13 - 2018-05-22 =
- Added just in time automated webinar type
- Added GDPR compliance elements
- Added new role called webinar moderator
- Added fullscreen option when controls are disabled
- Added customization functionality for 'New Registration' and 'Registration Confirmation' email in E-mail settings options
- Added webinar ticket login url in default email templates
- Added MySQL and PHP version check and notice
- Improved shortcode Thankyou page redirection flow
- Improved recurring webinar workflow
- Fixed autoplay on Apple Iphone
- Fixed issues with RTMP streaming 
- Fixed styling of admin bar for mobile devices
- Fixed issue with webinar login on different browsers
- Fixed incorrect 'Registered For' date in attendees List for 'One time webinars'
- Fixed compatibility issue with php 7.0.24
- Fixed webinar ticket list responsive issue
- Fixed compatbility issue with MySQL
- Fixed Only send one confirmation email for paid webinars


= Version 2.12.1 - 2017-11-26 =
- Improved compatibility with WordPress 4.9
- Improved URL from HOA button on live and replay page settings
- Improved URL from YT Live button on live and replay page settings
- Fixed incorrect date and time in reminder emails for recurring webinars


= Version 2.12 - 2017-11-09 =
- Added custom fields for registration form
- Added integration with MailPoet 3 
- Add attendees link in quick menu
- Add chatlog link in quick menu
- Add new button to start broadcast from Youtube site on webinarpages
- Improved double opt-in for Mailchimp which is disabled now
- Improved Dutch translation
- Improved multiply webinar tickets are now in the same tickets overview on my account page
- Improved preview page url’s only visible for webinar host
- Improved webinar type descriptions
- Improved webinar source labels and descriptions
- Fixed date/time in webinar widgets
- Fixed allowing previewing pages for other (assigned) user roles then administrator
- Fixed the styling of form when logged in with incorrect email
- Fixed error notice in registration shortcode when WooCommerce isn’t enabled.
- Fixed saving the email settings when removing the plugin
- Fixed non-numeric value in cookie function
- Fixed conflict with active campaign api
- Fixed issue with calendar button on thank you page
- Removed HOA start button from webinarpages


= Version 2.11.2 - 2017-07-29 =
- Add a "ticket sales is closed" message to shortcode output if woocommerce is inactive.
- Fixed small bugs


= Version 2.11.1 - 2017-07-26 =
- Fixed error on sites where WooCommerce isn't installed


= Version 2.11 - 2017-07-26 =
- Added Drip integration
- Added timezone in reminder emails
- Added other add to calendar buttons on thank you page
- Improved paid webinar process for attendee, which is added to attendee list automatically
- Improved paid webinar process for webinar host, which can skip the paywall before accessing the webinar
- Fixed time format in 1 hour reminder mail when webinar is recurring
- Fixed small bugs


= Version 2.10.1 - 2017-05-17 =
- Added permission to view the chatlog for user roles other then administrator 
- Fixed timezone issue for on demand webinars 
- Fixed conflict with LearnPress plugin


= Version 2.10 - 2017-04-13 =
#### Added
- 1 login per email address. When logged in with an email that is already logged in, the first user will be automatically logged out from the webinar.
- Webinar dashboard with stats of last 5 webinars
- Column 'Attended' to the attendee CSV export functionality.
- Columns 'Registered On', 'Registered For', 'Attended' to the attendee BCC export functionality.

#### Improved
- Recurring webinar times can be set every 5 minutes instead of 10
- System status report
- RTL support
- Updated localization
- Max quantity = 1 for Webinar WooCommerce products
- Allowing Cross Origin Resource Sharing

#### Fixed
- WooCommerce cart URL in shortcode
- Timezone issue with ‘right now’ timeslot
- Admin permission issue 
- Small bug fixes


= Version 2.9.3 - 2017-03-02 =
#### Fixed
- Add to cart URL for WooCommerce links
- Add to cart quantity for paid webinars


= Version 2.9.2 - 2017-01-30 =
#### Added
- Switch on settings page to use or disable the styles of the current theme (to prevent conflicting).
- Classes to form output registration shortcode
- Classes to form output login shortcode

#### Improved
- Sort the order of dates in the output of registration shortcode
- Call to cronjob from ‘every 1 minute’ to ‘every 5 minutes’

#### Fixed
- Column ‘registered for’ not exported in csv file
- Correct time for live webinars in attendee list column 
- License class to prevent conflicting with other plugins using default EDD class


= Version 2.9.1 - 2016-12-29 =
#### Added
- Capability to see control bar for other roles then administrator 
- ‘Automated’ status in control bar, webinar settings and webinar overview when webinar is automated.

#### Improved
- Performance under the hood, thanks Henrik!
- ActiveCampaign integration

#### Fixed
- Showing todays date when adding attendee to webinar from backend as an admin
- Icon in menu
- Time annotation in confirmation email
- Advanced access for other user roles
- Time annotation in ‘registered for’ column on attendees page


= Version 2.9 - 2016-11-11 =
#### Added
* Day offset for recurring webinars
* New media player which is non clickable 
* Functionality that play and mute button are only visible for webinar host, even if player has no buttons for attendees.
* Webinar simulation functionality
* Support for Youtube Live (custom stream type)
* Functionality to hide the social share box on the thankyou page
* Webinar url to calendar items

#### Improved
* Improved and updated French translation, thanks Julia Galindo!

#### Fixed
* Recurring webinars can also be registered for on the same day
* Mp4 url’s with symbols in it are being loaded correctly
* First private message notification not showing in message center for webinar host
* Conflict with Divi 3 icons
* Conflict with Monarch plugin
* Conflict with Serif Lite theme
* Issue with GetResponse


= Version 2.8 - 2016-09-23 =
#### Added
* Access tab which let you configure which users/members have access to the webinar
* Integration with WooCommerce Member
* Option to manually add attendees as the webinar host
* Option to import a CSV of attendees as the webinar host
* Functionality to see who have actually attended the webinar
* Functionality to see for which webinar timeslot a visitor is registered for
* Custom button text parameter in registration and login shortcodes
* Functionality to delete private questions 1 by 1 or clear whole log at once.
* Dates next to days in registration shortcode for recurring webinars.
* Norwegian translation (Contributed by Bjørn Handeland, thanks!)
* Estonian translation (Contributed by Peeter Jürgenson, thanks!)
* Japanese translation (Contributed by Takashi Inohara, thanks!)

#### Improved
* Upcoming webinar widget which won’t show closed webinars anymore

#### Fixed
* Issue with Lastpoint theme


= Version 2.7.1 - 2016-08-02 =
* Improved the ‘join webinar button’ so it will also show on ‘countdown’ status
* Fixed issue with past webinar widget
* Fixed conflict with Customizr theme


= Version 2.7 - 2016-07-29 =
#### Added
* Widget with upcoming webinars
* Widget with past webinars
* URL redirect to custom thank you page in registration shortcode
* Finnish translation (Contributed by Eeva Määttänen, thanks!)
* New page with chat logs from live chat
* Possibility to clear all the live chat messages at once
* Possibility to delete a public chat message from the frontend of the webinar as a webinar host.
* Tooltips for the control bar
* Date next to recurring timeslots
* Option to adjust the color of the live chat button
* A naked webinar link for text-only WooCommerce e-mails

#### Improved
* Duplicating webinars won’t copy the existing amount of views anymore
* Multisite network page and license activation for subsites
* Using WordPress default time format for showing time and dates.
* Ajax requests usage 
* Disabled the archive page for webinars post type, so you can create a page with permalink ‘webinars’

#### Fixed
* Issue with access press anonymous post plugin
* Linkedin share button was’t working correctly
* Private messages sent from live chat box are now available in the private message log also
* MailChimp issue which causes a blank screen when the attendee was already on the Mailinglist before signing up for the webinar.


= Version 2.6.2 - 2016-06-10 =
#### Added
* Webinar tickets table on my account page WooCommerce
* Notice about webinar ticket on order received page in WooCommerce
* Text fields on the registration page for paid webinars
* Timeslots on registration page for paid webinars
* Animation for control bar icons for better UX

#### Improved
* System report with PHP memory limit value
* Error when no webinar date or time is set. Error is hidden on frontend, and there is a admin notice on the backend about the webinar in particular.

#### Fixed
* Error which showed on backend after hiding the content or description box on the registration page
* Issue with height of video content in certain wp environments
* Issue with setting tabs which wouldn’t open/show on certain wp environments
* Some missing values when exporting webinar themes
* Issue with the preview email functionality 


= Version 2.6.1 - 2016-05-07 =
#### Improved
* System report now contains some time settings of your server too for better debugging

#### Fixed
* The “join webinar in progress” button is back again on the thankyou page for the webinar ‘right now’ timeslot
* The correct webinar time is now in the confirmation email for one time automated webinars
* Asking a question in Firefox won’t reload the page during a webinar anymore


= Version 2.6 - 2016-04-29 =
#### Added
* Call to action buttons and text field on live & replay page
* Shortcode for registration form
* Shortcode for login form 
* Switch to activate the forwarding of private questions to an email address
* Switch to show/hide the webinartitle on live & replay page
* Switch to hide description box on registration page
* Switch to hide content box on registration page
* Functionality to align registration and login form to left/center/right of the registration page, when content box is hidden.
* Action box icon in webinar control bar
* Description box icon in control bar
* Hebrew (עִבְרִית) translation (Contributed by Avi Paz, thanks!)
* Bulgarian (Български) translation (Contributed by Radoslav Raychev, thanks!)
* Danish (Dansk) translation (Contributed by Christian Dahlgaard, thanks!)

#### Improved
* Alignment of all boxes on live and replay page
* Moved WooCommerce integration switch to new integration tab on setting page of plugin
* Shadow on chatbox fields for better contrast on light background colors.
* System report 
* Text color in incentive box

#### Fixed
* Conflict with Aweber forms plugin which causes an error
* Line breaks in the description box
* Reminder e-mails for automated webinars
* Countdown timer not showing in certain situations


= Version 2.5.1 - 2016-03-21 =
* Replaced licensing functionality (Please obtain your new license from getwebinarpress.com)
* Bugfixes


= Version 2.5 - 2016-03-16 =
#### Added
* Support for mp4 files
* Support for iframe
* Support for RTMP streams
* ActiveCampaign integration
* Start webinar ‘right now’
* Realtime incentive box handling from live and replay page

#### Improved
* RTL support
* Code structure
* Configuration for max amount recurring webinars to show in registration form
* Alignment countdown page
* Time notation from 12h to 24h on some locations
* Its now possible to use shortcodes in email subject lines
* All webinars will be shown now in question dropdown list.
* Responsive issues on live page
* Configure maximum amount of timeslots on the registration page for recurring webinars.
* Added quotation marks around webinar title on countdown page and calendar events.

#### Fixed
* Authentication problem which caused attendees to login to other webinars then they had signed up for
* Lots of bug fixes


= Version 2.4.1 - 2016-02-12 =
#### Added
* Support for different timezones, so your webinar can run in a certain timezone while your website default is in another.
* French translation (Contributed by Florent Souyris, thanks!)

#### Improved
* Import / Export function now also exports the general webinar settings and content sources for correct import in your new webinar.
* Amount of webinars shown in attendee list doesn’t have a limit anymore.

#### Fixed
* Webinar export functionality which skipped to export a few settings.
* Charset in mail so special characters will be shown now
* HTML email issues 
* Conflicting styling issues with other plugins
* Bugfixes


= Version 2.4 - 2016-01-20 =
#### Added
* Raise hand functionality so you can interact with your attendees
* WooCommerce integration so you can sell webinar tickets and offer paid webinars
* Hangouts on Air button for faster Livestream creation
* Russian translation (Contributed by Oleksandr Terèkhôv, thanks!)
* Ukrainian translation (Contributed by Oleksandr Terèkhôv, thanks!)
* Persian translation (Contributed by Reza Maleki, thanks!)
* Spanish (Spain) translation (Contributed by Cecilio de Leevel, thanks!)
* Spanish (Argentina) translation (Contributed by Reuben Castrol, thanks!)
* Swedish translation (Contributed by Per Sparf, thanks!)

#### Improved
* Styling options for the replay page
* Styling options for the live page
* Styling and margin on the thank you page
* Attendee list now has a scrollbar 
* Styling of the live chat button
* Icon color in control bar are now grey when not active.

#### Fixed
* Background-color setting on thank you page is working now
* Default text on webinar login button
* Lots of bug fixes


= Version 2.3.1 - 2015-12-22 =
* Fixed automatic refresh for recorded webinars


= Version 2.3 - 2015-12-16 =
#### Added
* Live chat functionality for attendees
* A message center for the webinar host to view private messages in the control bar on the webinar page
* A login tab on the registration page for attendees to login when they have already registered (for example on another device)
* GetResponse integration
* Aweber integration
* Functionality to style the 'ask question' button on the webinar page 
* Functionality to export all questions as a text file 
* Greek translation (Contributed by Ivana Simic from WP WebinarSystem, thanks!) 
* Italian translation (Contributed by Gabriele Lo Cicero, thanks!) 
* Polish translation (Contributed by Marek Rusak, thanks!) 
* Portuguese translation (Contributed by Sofia Morgado, thanks!) 
* Romanian translation (Contributed by Corneliu Nicoara, thanks!) 
* Serbian translation (Contributed by Ivana Simic from WP WebinarSystem, thanks!) 
* Slovak translation (Contributed by Tomáš Bielik, thanks!)

#### Improved
* Compatibility with WordPress 4.4
* Compatibility on Windows servers
* Import/export function so images will be transferred also
* Questions page so it's possible to select and delete questions 
* Attendee list, it's now possible to delete multiply registrations 
* System report output
* Question form, the fields are now pre-populated with the details of the attendee
* When registration for webinar is disabled, visitor now will see a message instead of nothing at all.
* More icons and functionality in the control bar for webinar host
* Vimeo content type. --> Using Vimeo as a videosource? Adjust your Vimeo links! Use the Vimeo video ID from now on! 

#### Fixed
* Issue with Google Calendar button 
* Content issue in description box that prevented using HTML in it. 
* Content issue in the incentive box 


= Version 2.2 - 2015-10-09 =
* Added one-time activation for multisite installations
* Added a live control bar for admins in the live page
* Added the German language
* Improved checkboxes to switches for better user experience


= Version 2.1.1 - 2015-09-16 =
* Fixed a bug in switching the options page tabs


= Version 2.1 - 2015-09-15 =
* Fixed font conflicts with the WordPress theme
* Introducing the Permission Controller, allows you to control capabilities of the plugin for user roles


= Version 2.0 - 2015-08-13 =
* Rebranded the plugin name to WP WebinarSystem Pro
* Recurring webinar support. 
* Added Vimeo Support.
* Added more options to customize webinar pages.
* Added Linkedin share icon.
* Added System Status Tab to help support team to serve you better.
* Improved performance with a new infrastructure.