(function (Drupal, once) {
  /**
   * Add current or past class to events.
   */
  Drupal.behaviors.calendarItemCurrentDate = {
    attach: function attach(context) {
      once('calendar-date', context.querySelectorAll('time.calendar-date')).forEach(function (calendarDate) {
        var dateTime = calendarDate.getAttribute("datetime");
        if (dateTime !== null) {
          // Set the time to 0, we only want to compare
          var today = new Date().setHours(0, 0, 0, 0);
          var eventDate = new Date(dateTime).setHours(0, 0, 0, 0);

          // If event date is less than today.
          if (eventDate < today) {
            calendarDate.closest('.calendar-item').classList.add('calendar-item--past');
          }
          // If event date is today.
          if (eventDate === today) {
            calendarDate.closest('.calendar-item').classList.add('calendar-item--current');
          }
        }
      });
    }
  };

})(Drupal, once);
