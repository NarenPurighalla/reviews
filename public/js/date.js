Date.prototype.getDiffStringToNow = function(){
    var now = new Date();
    var diffInMS = now.getTime() - this.getTime();

    //Hack to prevent errors due to non syncronised client system time (add tolerance on 1min)
    if(diffInMS < 0 && diffInMS > -60000)
        diffInMS = 0;

    var secs = diffInMS/1000;
    var mins = secs/60;
    var hrs = mins/60;
    var days = hrs/24;

    var parts  = [];
    days = Math.floor(days);
    if(days > 0){
        parts.push(days);
        if(days == 1)
            parts.push("day");
         else
            parts.push("days");
    }else{
        hrs = Math.floor(hrs)%24;
        if(hrs > 0){
            parts.push(hrs);
            if(hrs == 1)
                parts.push("hr");
            else
                parts.push("hrs");
        }
    
        mins = Math.floor(mins)%60;
        if(mins >= 0){
            if(mins == 0)
                mins=1;
            parts.push(mins);
            if(mins == 1)
                parts.push("min");
            else
                parts.push("mins");
        }
 
        
    }
    var str = parts.join(' ');
    return str;
};


//Start of Date module
function getRBMonth(d) {
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[d.getMonth()];
}

Date.prototype.addMins = function (mins) { this.setMinutes(this.getMinutes() + mins); return this; };
Date.prototype.addHours = function (hours) { this.setHours(this.getHours() + hours); return this; };
Date.prototype.addDays = function (days) { this.setDate(this.getDate() + days); return this; };
Date.prototype.addMonths = function (months) { this.setMonth(this.getMonth() + months); return this; };
Date.prototype.addYears = function (years) { this.setFullYear(this.getFullYear() + years); return this; };

Date.prototype.toDateOnly = function(){
    var dateOnly  = new Date(this.getFullYear(), this.getMonth(), this.getDate());
    return dateOnly;
};


Date.prototype.toSWXString = function () {
    return ((this.getDate() >= 10 ? this.getDate() : '0' + this.getDate()) + "-" + getRBMonth(this) + "-" + this.getFullYear());
};

Date.prototype.toSWXTimeString = function () {

    var hours = this.getHours();
    var mins = this.getMinutes();

    var hoursString = hours > 12 ? hours - 12 : hours;
    if(hoursString < 10){
        hoursString = "0" + hoursString;
    }

    var minString = mins;
    if(minString < 10){
        minString = "0" + minString;
    }

    var AMPMString = hours < 12 ? 'AM' : 'PM';

    return hoursString + ':' + minString + ' ' + AMPMString;
};

Date.prototype.toSWXDateTimeString = function(){
    return this.toSWXString() + ' ' + this.toSWXTimeString();
};

Date.getFromSWXDate = function (str) {
    var months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    var arr = str.split('-');
    if (arr.length == 3){
        var d = new Date(parseInt(arr[2], 10), months.indexOf(arr[1].toUpperCase()), parseInt(arr[0], 10));
        d.addMins(-d.getTimezoneOffset());
        return d;
    }
         
    else
        return undefined;
};

Date.fromSWXString = function (str) {
    var months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    var arr = str.split('-');
    if (arr.length == 3){
        var d = new Date(parseInt(arr[2], 10), months.indexOf(arr[1].toUpperCase()), parseInt(arr[0], 10));
        d.addMins(-d.getTimezoneOffset());
        return d;
    }
         
    else
        return undefined;
};

Date.getDurationString = function(durationInMins){

    var hoursString = Math.floor(durationInMins / 60);
    if(hoursString < 10){
        hoursString = "0" + hoursString;
    }

    var minString = durationInMins % 60;
    if(minString < 10){
        minString = "0" + minString;
    }

    return hoursString + ':' + minString + ' Hrs';
};

Date.getTimeDifferenceInMins = function(a,b){
    var diff = b.getTime() - a.getTime();
    var diffMins = Math.floor(diff / (1000 * 60));

    return diffMins;
};

Date.getTimeDifferenceString = function (a, b) {
    var diffMins = Date.getTimeDifferenceInMins(a,b);
    return Date.getDurationString(diffMins);
};
Date.toDateFromJson = function(dateString) {
    return new Date(parseInt(dateString.substr(6),10)).getLocalEquivalent();
};

Date.prototype.getLocalEquivalent = function(){
    return this.addMins(this.getTimezoneOffset()).addMins(330);
};

/* date in long format*/
function dateInLong(d){ 
    var DOY = Math.ceil((new Date(d) - new Date(new Date(d).getFullYear(),0,1)) / 86400000);
    var Year = new Date(d).getFullYear() * 1000;
        return (DOY + Year) ;
}  

/*datetime to unix time format*/
function unixTimeStamp(dToUnix){
    return Math.floor(new Date(dToUnix).getTime()/1000.0);
}
//End of Date module

Date.prototype.equalsMonth = function (d) { 
    return (d.getFullYear() == this.getFullYear() && d.getMonth() == this.getMonth()); 
};
Date.prototype.equalsDate = function (d) { 
    return (d.getFullYear() == this.getFullYear() && d.getDate() == this.getDate() && d.getMonth() == this.getMonth());
};

Date.prototype.toIndianString = function(){
    var day  = this.getDate();
    var month = this.getMonth()+1;
    var year = this.getFullYear();
    if(month < 10)
        month = '0' + month;
        
    if(day < 10)
        day = '0' + day;
        
    var str = day + '/' + month + '/' + year;
    
    return str;
    
};

Date.prototype.toStandardDateString = function(){
    var day  = this.getDate();
    var month = this.getMonth()+1;
    var year = this.getFullYear();
    if(month < 10)
        month = '0' + month;
        
    if(day < 10)
        day = '0' + day;
        
    var str = year + '-' + month + '-' + day;
    
    return str;
    
};

Date.prototype.toAPITimeString = function(){
    var date = new Date(this);
    var timeZoneOffset = date.getTimezoneOffset();
    date = date.addMins(timeZoneOffset);

    var hours  = date.getHours();
    var mins = date.getMinutes();
    var seconds = date.getSeconds();

    if(hours < 10)
        hours = '0' + hours;
        
    if(mins < 10)
        mins = '0' + mins;

    if(seconds < 10)
        seconds = '0' + seconds;
        
    var str = hours + ':' + mins + ':' + seconds;
    
    return str;
};

Date.prototype.toAPIDateString = function(){
    var date = new Date(this);
    var timeZoneOffset = date.getTimezoneOffset();
    date = date.addMins(timeZoneOffset);

    var day  = date.getDate();
    var month = date.getMonth()+1;
    var year = date.getFullYear();

    var hours  = date.getHours();
    var mins = date.getMinutes();
    var seconds = date.getSeconds();

    if(month < 10)
        month = '0' + month;
        
    if(day < 10)
        day = '0' + day;
        
    var str = year + '-' + month + '-' + day;
    
    return str;
};

Date.prototype.toAPIDateTimeString = function(){
    var str = this.toAPIDateString() + ' ' + this.toAPITimeString(); 
    return str;
};

Date.parseAPIDateTimeString = function(str){
    var dateTimeParts = str.split(' ');

    var datePart = dateTimeParts[0];
    var timePart = dateTimeParts[1];

    //Date Parse
    var dateParts = datePart.split('-');
    var timeParts = timePart.split(':');
    var date = new Date(
                    parseInt(dateParts[0], 10), 
                    parseInt(dateParts[1], 10) -1, 
                    parseInt(dateParts[2], 10),
                    parseInt(timeParts[0], 10),
                    parseInt(timeParts[1], 10),
                    parseInt(timeParts[2], 10)
                );

    var timeZoneOffset = date.getTimezoneOffset();
    date = date.addMins(-timeZoneOffset);

    return date;
};

Date.getTimezone = function(){
    var d = new Date();
    var offset = -d.getTimezoneOffset();
    return offset;
};
