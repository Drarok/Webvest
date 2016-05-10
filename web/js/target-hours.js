(function () {
  'use strict';
  // console.log(targetPerDay);
  // console.log(loggedHours);

  var ctx = document.getElementById('chart').getContext('2d');

  var today = new Date();

  var numberOfDays = (function () {
    var endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    return endOfMonth.getDate();
  }());

  var totalHours = 0;

  var days = [];
  var daysData = [];
  for (var i = 1; i <= numberOfDays; ++i) {
    days.push(i);

    var currentDate = new Date(today.getFullYear(), today.getMonth(), i);
    if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) {
      totalHours += targetPerDay;
    }

    daysData.push(totalHours);
  }

  var data = {
    labels: days,
    datasets: [
      {
        label: 'Target',
        fill: false,
        lineTension: 0.1,
        backgroundColor: 'rgba(75, 192, 192, 0.4)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderCapStyle: 'butt',
        borderDash: [],
        borderDashOffset: 0.0,
        borderJoinStyle: 'miter',
        pointBorderColor: 'rgba(75, 192, 192, 1)',
        pointBackgroundColor: '#fff',
        pointBorderWidth: 1,
        pointHoverRadius: 5,
        pointHoverBackgroundColor: 'rgba(75, 192, 192, 1)',
        pointHoverBorderColor: 'rgba(220, 220, 220, 1)',
        pointHoverBorderWidth: 2,
        pointRadius: 1,
        pointHitRadius: 10,
        data: daysData
      },
      {
        label: 'Actual',
        fill: false,
        lineTension: 0.1,
        backgroundColor: 'rgba(75, 192, 75, 0.4)',
        borderColor: 'rgba(75, 192, 75, 1)',
        borderCapStyle: 'butt',
        borderDash: [],
        borderDashOffset: 0.0,
        borderJoinStyle: 'miter',
        pointBorderColor: 'rgba(75, 192, 75, 1)',
        pointBackgroundColor: '#fff',
        pointBorderWidth: 1,
        pointHoverRadius: 5,
        pointHoverBackgroundColor: 'rgba(75, 192, 75, 1)',
        pointHoverBorderColor: 'rgba(220, 220, 220, 1)',
        pointHoverBorderWidth: 2,
        pointRadius: 1,
        pointHitRadius: 10,
        data: loggedHours
      }
    ]
  };

  var options = {};

  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: options
  });
}());
