
/**
* random string generator
**/
function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

/**
* @param file_input reference to file upload element
* @param file_upload_path the path to set when upload to amazon s3
*/
function sendFiles(file_input, file_upload_path){
	console.log("Input", file_input);
	var data = new FormData();
	var count = 1;
	$.each($(file_input)[0].files, function(i, file){
		console.log("Should be appending this file", file);
		data.append('file-'+i, file);
		console.log("After appending data", data);
		count++;
	});
	data.append("num_files", count);
	$.ajax({
		url: site_url+"/attachments/upload_file/"+file_upload_path,
		data: data,
		cache: false,
		contentType: false,
		processData: false,
		type: 'POST',
		success: function(data){
			console.log(data);
		}
	});
}

/**
* initializes from and to date inputs
* @param from_date_id the id of the from date field
* @param to_date_id the id of the to date field
* @param disable_before_today boolean to signify if dates before today should be disabled
*/
function create_start_to_date_fields (from_date_id, to_date_id, options) {
	var start_date_options = {};
	/** date picker for start and end date **/
	
	var $start_date, $end_date = null;
	
	if(options.disable_before_today){
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		start_date_options = {
			onRender: function(date) {
				return date.valueOf() < now.valueOf() ? 'disabled' : '';
			}
		};
	}
	
	$start_date = $("#"+from_date_id).datepicker(start_date_options).on('changeDate', function(ev) {
		if (ev.date.valueOf() > $end_date.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			$end_date.setValue(newDate);
		}
		$start_date.hide();
		$("#"+to_date_id)[0].focus();
	}).data('datepicker');
	
	$end_date = $("#"+to_date_id).datepicker({
			onRender: function(date) {
				return date.valueOf() <= $start_date.date.valueOf() ? 'disabled' : '';
			}
	}).on('changeDate', function(ev) {
		$end_date.hide();
	}).data('datepicker');
}

/**
* sql date format is YYYY-MM-DD
* we return a javascript date object
* @param sql date in sql date format
* @return date javascript date object from sql date
*/
function convert_sqldate_to_date (sqldate) {
	sqldatearr = sqldate.split('-');
	return new Date(sqldatearr[0], sqldatearr[1]-1, sqldatearr[2]);
}


/**
 * Increment the number of times a port was used in a search
 * @param {Object} port_id, the id of the port to increment
 */
function update_port_hit_count (port_id) {
  $.post(site_url+"/services/increment_port_hit_count", {port_id: port_id});
}


/**
 * convert first letter of each word to upper case
 * @param {Object} str, the string to convert to upper case
 */
function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}


/**
 * Sets up ejs engine for bootstrap typeahead
 */
var ejs = {};

ejs.compile = function(template){
	var compiled = new EJS({url: template});
	return compiled;
}


/**
 * utility function to check 
 * if element is in scrollable view
 * @param {Object} elem
 */
function isScrolledIntoView(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

/**
 * find if envirionment is phone, tablet or desktop
 * useful for using javascript
 */
function findBootstrapEnvironment() {
	   	var envs = ['phone', 'tablet', 'desktop'];
	
	    $el = $('<div>');
	    $el.appendTo($('body'));
	
	    for (var i = envs.length - 1; i >= 0; i--) {
	        var env = envs[i];
	
	        $el.addClass('hidden-'+env);
	        if ($el.is(':hidden')) {
	            $el.remove();
	            return env;
	        }
	    };
}

/**
 * returns if mobile device or note 
 */
function isMobileDevice(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) )
 		return true;
	else
		return false;
}


/**
 * transformation function
 * from port result object
 * to datum for twitter typeahead
 * @param {Object} port
 */
function transform_port_to_datum(port) {
	var name = port.name;
	var tokens = new Array();
	tokens.push(port.name);
	if (port.state && /\S/.test(port.state)) {
		name += ", " + port.state;
		tokens.push(port.state);
	}
	name += ", " + toTitleCase(port.country_name);
	tokens.push(port.country_name);
	tokens.push(port.country_code);

	var transport_icons = new Array();
	var transport_icon_path = base_url + "assets/img/transport_icons/";
	var icon_size = 24;
	if (port.rail == 1) {
		transport_icons.push("icon-train");
	}
	if (port.road == 1) {
		transport_icons.push("icon-truck");
	}
	if (port.airport == 1) {
		transport_icons.push("icon-plane");
	}
	if (port.ocean == 1) {
		transport_icons.push("icon-anchor");
	}

	return {
		value : name,
		tokens : tokens,
		id : port.id,
		type : "port",
		flag : port.country_code.toLowerCase(),
		port_code : port.country_code + port.port_code,
		transport_icons : transport_icons
	};
}

/**
 * transformation function from city
 * result object to port object
 * @param {Object} city
 */
function transform_city_to_datum(city) {
	var name = city.city_name;
	var tokens = new Array();
	tokens.push(city.city_name);
	if (city.state && /\S/.test(city.state)) {
		name += ", " + city.state;
		tokens.push(city.state);
	}
	name += ", " + toTitleCase(city.country_name);
	tokens.push(city.country_name);
	tokens.push(city.country_code);

	return {
		value : name,
		tokens : tokens,
		id : city.id,
		type : "city",
		flag : city.country_code.toLowerCase()
	};
}

function transform_port_group_to_datum(port_group){
	var tokens = new Array();
	tokens.push(port_group.name);
	return {id: port_group.id, value: port_group.name, tokens: tokens, type:"port_group"};
}


