=== Subbub ===
Contributors: subbub
Donate link: https://subbub.org
Tags: writing, submissions, markets, competitions
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.7
Requires PHP: 5.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin provides shortcodes to interact with the Subbub submissions management system.

== Description ==

Subbub is a submissions management system for use by literary competitions and other markets. It provides a mechanism for the organisers of literary competitions and other markets to receive submissions from writers and to manage those submissions throughout the entire selection process. It may also handle submission fees if required and the display of results once the selection process has concluded.

The plugin currently supports two shortcodes.

= subbub_submit =

This is used to provide a button for use by writers submitting to your market. This will take them to a branded page on the Subbub website where they can upload their submission. Once the submission has been made, the writer will be returned to the page where the button is located.

The selection of submission target may be made at a number of levels: market, event or category. A market may be something like The Bridport Prize or Granta. An event is something like the 2023 Bridport Prize or Granta Issue 161. A category would be something like Short Stories or Poetry.

If the selection is specified at market level and there are multiple events, the writer has the opportunity to select an event and then a category if there are multiple categories. If it's made at event level and there are multiple categories, the writer may select a category. Targets are specified by number. The owner of a market may find out its number by inspecting it on the Subbub website.

The following options are available with the subbub_submit shortcode:

* **market** - This may be used to specify the number of the market to submit to - Example: [subbub_submit market=23]
* **event** - This may be used to specify the number of the event to submit to - Example: [subbub_submit event=105]
* **category** - This may be used to specify the number of the event to submit to - Example: [subbub_submit category=213]
* **newwindow** - If this is set to 1, the page on Subbub will open in a new window - Example: [subbub_submit category=213 newwindow=1]
* **align** - If this is set to center, the button is center-aligned - Example: [subbub_submit category=213 align=center]

= subbub_results =

This is used to provide the very latest results from an entire market, one event or just a single category. Up until the final stage, entries are anonymised and only the titles are identified.

Entries are displayed in a simple table, with the placing in the first column and the title (with or without the author's name) in the second.

The following options are available with the subbub_results shortcode:

* **market** - This may be used to specify the number of the market to show the results for - Example: [subbub_results market=23]
* **event** - This may be used to specify the number of the event to show the results for - Example: [subbub_results event=105]
* **category** - This may be used to specify the number of the event to show the results for - Example: [subbub_results category=213]

== Frequently Asked Questions ==

= If I'm just interested in submissions to, or results from, a single category, do I need to specify the market and event numbers as well? =

No, category numbers are unique, as indeed are event numbers.

== Screenshots ==

1. This is how you create a button to permit submissions.
2. This is what the button looks like.
3. This is how you create a results display.
4. This is what the results look like (start only).

== Changelog ==

= 1.0.7 =
* Add links to winning entries and judges report if present in results.

= 1.0.6 =
* Make function names unique by prepending subbub_.

= 1.0.5 =
* Sanitize fields from $_SERVER.

= 1.0.4 =
* Added support for subbub_results.
* Change name of subbubbutton to subbub_submit.

= 1.0.3 =
* Store logo for subbub_submit button in library directory.

= 1.0.2 =
* Adoption to align button centrally.

= 1.0.1 =
* Add ability to specify new window when opening up Subbub.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.4 =
* Standardisation of shortcode names.
