const colname= '<?php echo json_encode($colname);?>'

var options = {
    series: [{
    name: 'Active',
    data: [44, 55, 41, 67, 22, 43, 21, 49]
  }, {
    name: 'Tardy',
    data: [13, 23, 20, 8, 13, 27, 33, 12]
  }, {
    name: 'Absent',
    data: [11, 17, 15, 15, 21, 14, 15, 13]
  },{
    name: 'Cutting',
    data: [11, 17, 15, 15, 21, 14, 15, 13]
  }],
    chart: {
    type: 'bar',
    height: 350,
    stacked: true,
    stackType: '100%'
  },
  responsive: [{
    breakpoint: 450,
    options: {
      legend: {
        position: 'bottom',
        offsetX: -100,
        offsetY: 0
      }
    }
  }],
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar','Apr','May', 'Jun', 'Jul', 'Aug',
      'Sep', 'Oct', 'Nov', 'Dec'
    ],
  },
  fill: {
    opacity: 1
  },
  legend: {
    position: 'bottom',
    horizontalAlign: 'center',
    offsetX: 40
  },
  };

  var chart = new ApexCharts(document.querySelector("#bar-chart"), options);
  chart.render();
