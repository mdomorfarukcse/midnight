 /*
      =================================
          Revenue Monthly | Options
      =================================
  */
  var options1 = {
    chart: {
      fontFamily: 'Nunito, sans-serif',
      height: 365,
      type: 'area',
      zoom: {
          enabled: true
      },
      dropShadow: {
        enabled: true,
        opacity: 0.2,
        blur: 10,
        left: -7,
        top: 22
      },
      toolbar: {
        show: false
      },
      events: {
        mounted: function(ctx, config) {
          const highest1 = ctx.getHighestValueInSeries(0);
          const highest2 = ctx.getHighestValueInSeries(1);
          const highest3 = ctx.getHighestValueInSeries(2);
          const highest4 = ctx.getHighestValueInSeries(3);
          const highest5 = ctx.getHighestValueInSeries(4);
          const highest6 = ctx.getHighestValueInSeries(5);
   
          ctx.addPointAnnotation({
            x: new Date(ctx.w.globals.seriesX[0][ctx.w.globals.series[0].indexOf(highest1)]).getTime(),
            y: highest1,
            label: {
              style: {
                cssClass: 'd-none'
              }
            },
            customSVG: {
                SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#000000" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                cssClass: undefined,
                offsetX: -8,
                offsetY: 5
            }
          })
  
          ctx.addPointAnnotation({
            x: new Date(ctx.w.globals.seriesX[1][ctx.w.globals.series[1].indexOf(highest2)]).getTime(),
            y: highest2,
            label: {
              style: {
                cssClass: 'd-none'
              }
            },
            customSVG: {
                SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ff0000" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                cssClass: undefined,
                offsetX: -8,
                offsetY: 5
            }
          })
  
          ctx.addPointAnnotation({
            x: new Date(ctx.w.globals.seriesX[2][ctx.w.globals.series[2].indexOf(highest3)]).getTime(),
            y: highest3,
            label: {
              style: {
                cssClass: 'd-none'
              }
            },
            customSVG: {
                SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#999999" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                cssClass: undefined,
                offsetX: -8,
                offsetY: 5
            }
          })
  
          ctx.addPointAnnotation({
            x: new Date(ctx.w.globals.seriesX[3][ctx.w.globals.series[3].indexOf(highest4)]).getTime(),
            y: highest4,
            label: {
              style: {
                cssClass: 'd-none'
              }
            },
            customSVG: {
                SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#aaaaaa" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                cssClass: undefined,
                offsetX: -8,
                offsetY: 5
            }
          })
          ctx.addPointAnnotation({
            x: new Date(ctx.w.globals.seriesX[4][ctx.w.globals.series[4].indexOf(highest5)]).getTime(),
            y: highest5,
            label: {
              style: {
                cssClass: 'd-none'
              }
            },
            customSVG: {
                SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#d9a300" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                cssClass: undefined,
                offsetX: -8,
                offsetY: 5
            }
          })
          
          ctx.addPointAnnotation({
            x: new Date(ctx.w.globals.seriesX[5][ctx.w.globals.series[5].indexOf(highest6)]).getTime(),
            y: highest6,
            label: {
              style: {
                cssClass: 'd-none'
              }
            },
            customSVG: {
                SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#535362" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                cssClass: undefined,
                offsetX: -8,
                offsetY: 5
            }
          })
        },
      }
    },
    colors: ['#000000', '#ff0000', '#999999', '#aaaaaa', '#d9a300', '#535362'],
    dataLabels: {
        enabled: false
    },
    markers: {
      discrete: [{
      seriesIndex: 0,
      dataPointIndex: 7,
      fillColor: '#000',
      strokeColor: '#000',
      size: 5
    }, {
      seriesIndex: 2,
      dataPointIndex: 11,
      fillColor: '#000',
      strokeColor: '#000',
      size: 4
    }]
    },
    subtitle: {
      text: '-',
      align: 'left',
      margin: 0,
      offsetX: 95,
      offsetY: 0,
      floating: false,
      style: {
        fontSize: '18px',
        color:  '#4361ee'
      }
    },
    title: {
      text: 'Araç Grafiği',
      align: 'left',
      margin: 0,
      offsetX: -10,
      offsetY: 0,
      floating: false,
      style: {
        fontSize: '18px',
        color:  '#0e1726'
      },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        width: 2,
        lineCap: 'square'
    },
    series: [
	{
        name: 'OEM Power',
        data: [11, 2, 3, 232, 524]
    }, 
	
	{
        name: 'OEM Torque',
        data: [321, 234, 3, 234, 352]
    }, 
	
	{
        name: 'Stage 1 Power',
        data: [23, 84, 3, 54, 845]
    }, 
	
	{
        name: 'Stage 1 Torque',
        data: [21, 412, 3, 612, 127]
    }, 
	{
        name: 'Stage 2 Power',
        data: [623, 231, 3, 425, 23]
    }, 
	
	{
        name: 'Stage 2 Torque',
        data: [523, 74, 16, 32, 612]
    }
	],
    labels: ['0', '500', '1000', '1500', '2000', '2500', '3000', '3500', '4000', '4500', '5000', '5500', '6000', '6500', '7000', '7500',  '8000',  '8500', '9000'],
    xaxis: {
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      },
      crosshairs: {
        show: true
      },
      labels: {
        offsetX: 0,
        offsetY: 5,
        style: {
            fontSize: '12px',
            fontFamily: 'Nunito, sans-serif',
            cssClass: 'apexcharts-xaxis-title',
        },
      }
    },
    yaxis: {
      labels: {
       
        offsetX: -22,
        offsetY: 0,
        style: {
            fontSize: '12px',
            fontFamily: 'Nunito, sans-serif',
            cssClass: 'apexcharts-yaxis-title',
        },
      }
    },
    grid: {
      borderColor: '#e0e6ed',
      strokeDashArray: 5,
      xaxis: {
          lines: {
              show: true
          }
      },   
      yaxis: {
          lines: {
              show: false,
          }
      },
      padding: {
        top: 0,
        right: 0,
        bottom: 0,
        left: -10
      }, 
    }, 
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      offsetY: -50,
      fontSize: '16px',
      fontFamily: 'Nunito, sans-serif',
      markers: {
        width: 10,
        height: 10,
        strokeWidth: 0,
        strokeColor: '#fff',
        fillColors: undefined,
        radius: 12,
        onClick: undefined,
        offsetX: 0,
        offsetY: 0
      },    
      itemMargin: {
        horizontal: 0,
        vertical: 20
      }
    },
    tooltip: {
      theme: 'dark',
      marker: {
        show: true,
      },
      x: {
        show: false,
      }
    },
    fill: {
        type:"gradient",
        gradient: {
            type: "vertical",
            shadeIntensity: 1,
            inverseColors: !1,
            opacityFrom: .28,
            opacityTo: .05,
            stops: [45, 100]
        }
    },
    responsive: [{
      breakpoint: 575,
      options: {
        legend: {
            offsetY: -30,
        },
      },
    }]
  }
  
    var chart1 = new ApexCharts(
      document.querySelector("#revenueMonthly"),
      options1
  );
  
  chart1.render();
  