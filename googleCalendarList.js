
google.load("gdata", "2.x");

function cal_init()
{
	google.gdata.client.init(cal_handleGDError);
}

/**
 * Adds a leading zero to a single-digit number.  Used for displaying dates.
 */
function cal_padNumber(num)
{
  if (num <= 9) {
    return "0" + num;
  }
  return num;
}

function cal_loadCalendar(calendarUrl)
{
  var service = new google.gdata.calendar.CalendarService('gdata-js-client-samples-simple');
  var query = new google.gdata.calendar.CalendarEventQuery(calendarUrl);
  query.setOrderBy('starttime');
  query.setSortOrder('ascending');
  query.setFutureEvents(true);
  query.setSingleEvents(true);
  query.setMaxResults(1024);

  service.getEventsFeed( query, cal_listEvents, cal_handleGDError );
}

function cal_handleGDError(e)
{
  document.getElementById('jsSourceFinal').setAttribute('style',
      'display:none');
  if (e instanceof Error) {
    /* alert with the error line number, file and message */
    alert('Error at line ' + e.lineNumber +
          ' in ' + e.fileName + '\n' +
          'Message: ' + e.message);
    /* if available, output HTTP error code and status text */
    if (e.cause) {
      var status = e.cause.status;
      var statusText = e.cause.statusText;
      alert('Root cause: HTTP error ' + status + ' with status text of: ' +
            statusText);
    }
  } else {
    alert(e.toString());
  }
}

function cal_addDate( dateInfo, dayInfo, timeInfo, descInfo, descInfo2 )
{
  var cal_body = document.getElementById( "cal_body" );
  var my_row = document.createElement( "tr" );
  cal_body.appendChild( my_row );

  var my_th = document.createElement( "td" );
  my_row.appendChild( my_th );

  var my_day = document.createElement( "td" );
  my_row.appendChild( my_day );

  var my_time = document.createElement( "td" );
  my_row.appendChild( my_time );

  var my_td = document.createElement( "td" );
  my_row.appendChild( my_td );

  var my_td2 = document.createElement( "td" );
  my_row.appendChild( my_td2 );

  if ( descInfo.search( /\(T\)/ )!=-1 )
	{
		descInfo = "<div style='padding:4px;background-color:white;border: 1px dashed black;'>"+descInfo.replace( /\(T\)/, "" )+"</div>";
	}

if ( descInfo.search( /\(A\)/ )!=-1 )
	{
		descInfo = "<div style='padding:4px;background-color:red;border: 1px dashed black;'>"+descInfo.replace( /\(A\)/, "" )+"</div>";
	}

var descInfo_formatted = descInfo2.replace( /\n/g, "<br/>" );

  my_th.innerHTML = dateInfo;
  my_day.innerHTML = dayInfo;
  my_time.innerHTML = timeInfo;
  my_td.innerHTML = descInfo;
//  my_td2.innerHTML = "<pre>"+descInfo2+"</pre>";
my_td2.innerHTML = descInfo_formatted;
}

function cal_addDivider( textInfo )
{
  var cal_body = document.getElementById( "cal_body" );
  var my_row = document.createElement( "tr" );
  cal_body.appendChild( my_row );

  var my_header = document.createElement( "td" );
  my_row.appendChild( my_header );
/*
  var my_day = document.createElement( "td" );
  my_row.appendChild( my_day );

  var my_time = document.createElement( "td" );
  my_row.appendChild( my_time );

  var my_td = document.createElement( "td" );
  my_row.appendChild( my_td );

  var my_td2 = document.createElement( "td" );
  my_row.appendChild( my_td2 );
	*/

  my_header.innerHTML = "<h1>"+textInfo+"</h1>";

  my_header.setAttribute("colSpan",5);
  my_header.setAttribute("class","googleMonth");
}

function cal_listEvents( feedRoot )
{
  	var entries = feedRoot.feed.getEntries();

  /* set the calendarTitle div with the name of the calendar */
  document.getElementById('calendarTitle').innerHTML = feedRoot.feed.title.$t;

  var indexMonthLast = 13;

  /* loop through each event in the feed */
  var len = entries.length;
  for (var i = 0; i < len; i++)
  {
    var entry 			= entries[i];
    var title 			= entry.getTitle().getText();
    var startDateTime 	= null;
    var startJSDate 	= null;
	var endJSDate 	= null;
    var times 			= entry.getTimes();
	var desc = entry.getContent().getText();

 	if ( times.length > 0 )
	{
      startDateTime = times[0].getStartTime();
      startJSDate = startDateTime.getDate();

	  var endDateTime = times[0].getEndTime();
	  endJSDate = endDateTime.getDate();
    }

    var entryLinkHref = null;
    if (entry.getHtmlLink() != null)
	{
      entryLinkHref = entry.getHtmlLink().getHref();
    }

	var indexMonth = startJSDate.getMonth();
	if ( indexMonth!=indexMonthLast )
	{
		switch ( indexMonth )
		{
			case 0: cal_addDivider( langMonth.January ); break;
			case 1: cal_addDivider( langMonth.February ); break;
			case 2: cal_addDivider( langMonth.March ); break;
			case 3: cal_addDivider( langMonth.April ); break;
			case 4: cal_addDivider( langMonth.May ); break;
			case 5: cal_addDivider( langMonth.June ); break;
			case 6: cal_addDivider( langMonth.July ); break;
			case 7: cal_addDivider( langMonth.August ); break;
			case 8: cal_addDivider( langMonth.September ); break;
			case 9: cal_addDivider( langMonth.October ); break;
			case 10: cal_addDivider( langMonth.November ); break;
			case 11: cal_addDivider( langMonth.December ); break;
		}

		indexMonthLast = indexMonth;
	}

	// note: add 1900 to year
	var dateString = "";
    dateString = startJSDate.getDate() + "/" + (startJSDate.getMonth() + 1)
		+ " - " + (startJSDate.getYear()+1900);

	if ( entryLinkHref!=null)
	{
		title = "<a target='_blank' href='" + entryLinkHref + "'>" + title +"</a>";
	}

	var timeInfo = "";
	if (!startDateTime.isDateOnly())
	{
      timeInfo = startJSDate.getHours() + ":" + cal_padNumber(startJSDate.getMinutes()) +" - "+ endJSDate.getHours() + ":" + cal_padNumber(endJSDate.getMinutes());
    }

	var day = "";
	var indexDay = startJSDate.getDay();
	switch ( indexDay )
	{
		case 0: day = langDay.Sunday; break;
		case 1: day = langDay.Monday; break;
		case 2: day = langDay.Tuesday; break;
		case 3: day = langDay.Wednesday; break;
		case 4: day = langDay.Thursday; break;
		case 5: day = langDay.Friday; break;
		case 6: day = langDay.Saturday; break;
	}

	cal_addDate(
		dateString,
		day,
		timeInfo,
		title,
        desc
    );
  }
}

google.setOnLoadCallback( cal_init );

/*
jQuery(document).ready(function() {
	alert( 1 );
    jQuery('#colorpicker').hide();
    jQuery('#colorpicker').farbtastic("#color");
    jQuery("#idHeadercolor").click(function(){jQuery('#colorpicker').slideToggle()});
  });
  
*/
/*
    jQuery('#colorpicker').hide();
    jQuery('#colorpicker').farbtastic("#color");
    jQuery("#idHeadercolor").click(function(){jQuery('#colorpicker').slideToggle()});
*/