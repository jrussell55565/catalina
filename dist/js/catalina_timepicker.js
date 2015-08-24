$(function(){
        $('#timepicker-default').timepicker();
          $('#timepicker-24hr').timepicker({
    minuteStep: 1,
    template: 'modal',
   appendWidgetTo: 'body',
    showSeconds: true,
    showMeridian: false,
    defaultTime: false,
    modalBackdrop: true
  });
});
