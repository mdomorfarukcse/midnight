<?php

use Pemm\Core\Container;
use Pemm\Model\Category;
use Pemm\Model\Vehicle;
use Pemm\Model\VehicleAdditionalOption;
use Pemm\Model\VehicleTuning;
use Pemm\Model\VehicleReadMethod;
use Pemm\Model\AdditionalOption;
use Pemm\Model\TuningAdditionalOption;
use Pemm\Model\Tuning;
use Pemm\Core\Language;

/* @var Container $container */
global $container;

/* @var Vehicle $vehicle */
$vehicle = $container->get('vehicle');

/* @var Language $language */
$language = $container->get('language');

$tuningOptions = [];

$methods = [];
$tuningTypes = [];

function percentCalculate($data, $rate) {
    return round(($data * $rate) / 100, 0);
}

function powerChartData($power)
{
    $chart = [];

    // 14 eleman olmalı
    foreach ([1, 15, 40, 60, 80, 90, 95, 100, 90, 1] as $rate) {
        $chart[] = percentCalculate($power, $rate);
    }

    return $chart;
}


function torqueChartData($torque)
{
    $chart = [];

    // 14 eleman olmalı
    foreach ([1, 50, 90, 98.25, 100, 95, 85, 79, 66, 1] as $rate) {
        $chart[] = percentCalculate($torque, $rate);
    }

    return $chart;
}

$chart[0] = ['name' => 'OEM Power', 'data' => $vehicle->hasOemPowerChart() ? explode(',', $vehicle->getOemPowerChart()): powerChartData($vehicle->getStandardPower())];
$chart[1] = ['name' => 'OEM Torque', 'data' => $vehicle->hasOemTorqueChart() ? explode(',', $vehicle->getOemTorqueChart()) : torqueChartData($vehicle->getStandardTorque())];

$i = 2;

$tuningHtml = '';
$minCredit = 0;

/* @var VehicleTuning $vehicleTuning */
$control = 0;
$row = 0;

$_vehicleTuningOptions = [];

foreach ($vehicle->tunings as $vehicleTuning) {

	if ($row > 0) {
		continue;
	}
	$row++;

    if ($vehicleTuning->getIsActive()) {

        $methods[] = $vehicleTuning->getMethod();
        $tuningTypes[] = $vehicleTuning->tuning->getName();
        $chart[$i] = ['name' => $vehicleTuning->tuning->getName() . ' Power', 'data' => $vehicleTuning->hasPowerChart() ? explode(',', $vehicleTuning->getPowerChart()) : powerChartData($vehicleTuning->getMaxPower())];
        $i++;
        $chart[$i] = ['name' => $vehicleTuning->tuning->getName() . ' Torque', 'data' => $vehicleTuning->hasTorqueChart() ? explode(',', $vehicleTuning->getTorqueChart()) : torqueChartData($vehicleTuning->getMaxTorque())];
        $tuningOptions[$vehicleTuning->tuning->getCode()]['name'] = $vehicleTuning->tuning->getName();
        $tuningOptions[$vehicleTuning->tuning->getCode()]['credit'] = $vehicleTuning->tuning->getCredit();

        if ($minCredit < $vehicleTuning->tuning->getCredit()) {
            $minCredit = $vehicleTuning->tuning->getCredit();
        }

        $tuningHtml = '<div class="tab-pane col-md-12" id="' . $vehicleTuning->getId(). '-tab" role="tabpanel" aria-labelledby="' . $vehicleTuning->getId(). '-tab"><div class="row">';

        foreach ($vehicle->getTuningsAll() as $tuningGet) {


            $tuningHtml .= '<div class="col-md-3" id="tuningTab' . $tuningGet->getId(). '">
                <button onclick="tuningSelect(this)" data-id="' . $tuningGet->getId(). '" data-title="' . $tuningGet->getName(). '" data-credit="' . $tuningGet->getCredit(). '" id="tuningButton' . $tuningGet->getId(). '" style="border-radius: 0px; width: 100%" type="button"
                        class="btn btn-primary btn-lg tuning-select-button">' . $tuningGet->getName(). '</button>';

            if (!empty($vehicleAdditionalOptions = $tuningGet->getOptions())) {

                /* @var VehicleAdditionalOption $vehicleAdditionalOption */
                foreach ($vehicleAdditionalOptions as $vehicleAdditionalOption) {

                    if ($vehicleAdditionalOption->getIsActive()) {
                        $tuningHtml .= ' <div class="row mt-3">
                        <div class="n-chk col-lg-12 col-md-12 mb-3 additional-option">
                            <label class="new-control new-checkbox checkbox-dark">
                                <input type="checkbox" data-credit="'. $vehicleAdditionalOption->getCredit() .'"
                                       onchange="calculateCredit(' . $tuningGet->getId(). ')"
                                       class="new-control-input ' . $tuningGet->getId(). '-option"
                                       name="tuning[options]['. $vehicleAdditionalOption->getId() .']" value="1"
                                       data-title="' . $vehicleAdditionalOption->additionalOption->getName() . '" data-symbol=""
                                       data-id="' . $vehicleAdditionalOption->getId() . '">
                                <span class="new-control-indicator"></span>' . $vehicleAdditionalOption->additionalOption->getName() . '
                            </label>
                        </div>
                        </div>';
                    }

                }

            }
                $tuningHtml .= '</div>';

        }

        $i++;

        $tuningHtml .= '</div></div>';

    }

}

$readingDeviceOptions = '';

$readDevices = [
"Bitbox",
"Byteshooter",
"Piasini",
"Frieling SPI Wizard",
"Alientech",
"Dimsport Genius",
"PEmicro",
"MPPS",
"Magic Motorsport",
"EVC",
"Frieling i-Flash",
"Frieling i-Boot",
"Dimsport New Trasdata",
"Autotuner",
"PCM-Flash",
"Femto",
"bFlash",
"CMD"
];


    /* @var VehicleReadMethod $vehicleReadMethod*/
    foreach ($readDevices as $name) {
        $readingDeviceOptions .= '<option value="' . $name . '">' . $name . '</option>';
    }


?>

<script>

    var vehicleId = "<?= $vehicle->getId() ?>";
    var vehicleFullName = "<?= $vehicle->getFullName() ?>";
    var vehicleTorque = "<?= $vehicle->getStandardTorque() ?>";
    var vehiclePower = "<?= $vehicle->getStandardPower() ?>";
    var vehicleTuningHtml = <?= json_encode($tuningHtml);?>;
    var minCredit = <?= $minCredit ?>


	 var readingDeviceSelect = $('select[name="read_device"]');
    readingDeviceSelect.html('<?= $readingDeviceOptions ?>');
    readingDeviceSelect.selectpicker('refresh');


    var options1 = {
        chart: {
            fontFamily: 'Nunito, sans-serif',
            height: 390,
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
                            SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#0d2d4c" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
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
                            SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#aaaaaa" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
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
                            SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#23008c" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
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
        colors: ['#ffffff', '#0d2d4c', '#999999', '#aaaaaa', '#23008c', '#535362'],
        dataLabels: {
            enabled: false
        },
        markers: {
            discrete: [{
                seriesIndex: 0,
                dataPointIndex: 7,
                fillColor: '#ffffff',
                strokeColor: '#ffffff',
                size: 5
            }, {
                seriesIndex: 2,
                dataPointIndex: 11,
                fillColor: '#ffffff',
                strokeColor: '#ffffff',
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
                color:  '#0d2d4c'
            }
        },
        title: {
            text: '<?= $language::translate('Vehicle Chart') ?>',
            align: 'left',
            margin: 0,
            offsetX: -10,
            offsetY: 0,
            floating: false,
            style: {
                fontSize: '18px',
                color:  '#000'
            },
        },
        stroke: {
            show: true,
            curve: 'smooth',
            width: 2,
            lineCap: 'square'
        },
        series: <?= json_encode($chart) ?>,
        labels: [<?= $vehicle->getRpm() ?>],
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
            offsetY: -10,
            fontSize: '12px',
            fontFamily: 'Nunito, sans-serif',
            markers: {
                width: 8,
                height: 8,
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
</script>
<div class="row">
    <div  style="margin-top:20px;"  class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <div class="row">
            <div   class="col-md-12 col-sm-12 col-12 glance-col">
                <div style="border: none;     background-color: #ffffff !important;" class="card component-card_1">
                    <div class="card-body">
                        <img style="width: 70px;    margin-bottom: 13px;" src="<?= $vehicle->brand->getImage(true) ?>">
                        <h5 style="    margin-bottom: 5px;"  class="card-title"><?= $vehicle->getFullName() ?></h5>
                        <p class="card-text"><?= $language::translate('Estimated %:power more power and %:torque more torque', [':power' => 30,':torque' => 40]) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <table style="width: 100%">
                    <tbody>
                    <tr>
                        <th class="duzeltmusti" style="background: #F6F6F6; color: #333333;">
                            <span style="border: none;" class="glance">
                                <span class="label"><?= $language::translate('Parameters') ?> </span>
                            </span>
                        </th>
                        <th class="duzeltmusti" style="  color: #333333;">
                            <span  class="glance">
                                <span   class="label"><?= $language::translate('Original') ?> </span>
                            </span>
                        </th>
                        <?php
                        $colorArray = ['#4361ee', '#387db3'];
                        /* @var VehicleTuning $vehicleTuning */
												$row = 0;
                        foreach ($vehicle->tunings as $key => $vehicleTuning) {
													if ($row > 0) {
														continue;
													}
													$row++;
                            if (!$vehicleTuning->getIsActive()) continue; ?>
                            <th class="duzeltmusti">
                            <span class="glance">
                                <span class="label"> <!-- $vehicleTuning->tuning->getName() */  --> Stage 1 </span>
                            </span>
                            </th>
                        <?php }
                        ?>
                    </tr>
                    <tr>
                        <td class="duzeltmusti" style="background: #F6F6F6; color: #333333;">
                            <span class="glance ayarla">
                                <span class="value"><?= $language::translate('Power') ?> (Bhp)</span>
                            </span>
                        </td>
                        <td class="duzeltmusti" style="    background: #e8e8e8  !important; color: #333333;">
                            <span style="text-align: center; color: #fff;" class="glance">
                                <span class="col-power"><?= $vehicle->getStandardPower() ?> <small style="    font-size: 12px;"></small></span>
                            </span>
                        </td>
                        <?php
												$row = 0;
                        foreach ($vehicle->tunings as $key => $vehicleTuning) {
													if ($row > 0) {
														continue;
													}
													$row++;
                            if (!$vehicleTuning->getIsActive()) continue; ?>
                            <td class="duzeltmusti" style=" background: <?= $colorArray[$key] ?> !important; color: #fff;">
                                <span style="text-align: center; color: #fff;" class="glance">
                                    <span style="color:white" class="col-power"> <?= $vehicleTuning->getMaxPower() ?><small style="  color:#fff;   font-size: 12px;"></small></span>
                                </span>
                            </td>
                        <?php }
                        ?>
                    </tr>
                    <tr>
                        <td class="duzeltmusti" style="background: #F6F6F6; color: #333333;">
                            <span class="glance ayarla">
                                <span class="value"><?= $language::translate('Torque') ?> (Nm)</span>
                            </span>
                        </td>
                        <td class="duzeltmusti" style="    background: #e8e8e8  !important; color: #333333;">
                            <span style="text-align: center; color: #232323;" class="glance">
                                <span class="col-torque"><?= $vehicle->getStandardTorque() ?> <small style="    font-size: 12px;"></small></span>
                            </span>
                        </td>
                        <?php
												$row = 0;
                        foreach ($vehicle->tunings as $key => $vehicleTuning) {
													if ($row > 0) {
														continue;
													}
													$row++;
                            if (!$vehicleTuning->getIsActive()) continue; ?>
                            <td class="duzeltmusti" style=" background: <?= $colorArray[$key] ?> !important; color: #fff;">
                                <span style="text-align: center; color: #232323;" class="glance">
                                    <span   style="color:white" class="col-torque"> <?= $vehicleTuning->getMaxTorque() ?><small style="  color:#fff;   font-size: 12px;"></small></span>
                                </span>
                            </td>
                        <?php }
                        ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div  style="margin-top:20px;"  class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <div class="widget widget-chart-one" style="border: none;" >
            <div class="widget-content">
                <div id="revenueMonthly"></div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12 col-md-12 col-sm-12 col-12">
    <h4><?= $language::translate('Engine Features') ?></h4>  </div>

<div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
    <div class="row">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget-content widget-content-area ">
                <table class="table table-striped mb-4">
                    <tbody>
                    <tr>
                        <td><?= $language::translate('Fuel Type') ?></td>
                        <td style="font-weight:bold;"><?= $vehicle->getFuel() ?></td>
                    </tr> 
				  
                    <tr>
                        <td><?= $language::translate('Tuning Type') ?></td>
                        <td style="font-weight:bold;"> Stage 1  <!-- implode(', ', $tuningTypes)  --> </td>
                    </tr>

                    <tr>
                        <td><?= $language::translate('Engine Ecu') ?></td>
                        <td style="font-weight:bold;"><?= $vehicle->getEcu() ?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
 

<div id="card_4" class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4><?= $language::translate('Additional Options') ?></h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="row">
                <?php

                $_vehicleAdditionalOption = [];

                /* @var VehicleAdditionalOption $tuningOption */
                $options = $vehicleTuning->getOptions();
                if (!empty($options)) {
                foreach ($options as $tuningOption) {
                    if ($tuningOption->getIsActive()) {
                        $_vehicleAdditionalOption[$tuningOption->getTuningAdditionalOption()->getAdditionalOption()->getId()] = [
                            'image' => $tuningOption->tuningAdditionalOption->additionalOption->getImage(true),
                            'name' => $tuningOption->tuningAdditionalOption->additionalOption->getName()];
                    }
                } 
                    foreach ($_vehicleAdditionalOption as $option) {
                        ?>
                        <div class="col-xl-3 col-md-3 col-sm-3 col-12 mb-2">
						
						<div class="card">
  <div style="    padding: 9px;" class="card-body">
    <div class="row">
      <div class="col-4 d-flex align-items-center">
        <img  style="    width: 100%;" src="<?= $option['image'] ?>"> 
      </div>
      <div class="col-8 d-flex align-items-center">
        <h5 class="card-title"><?= $option['name'] ?></h5>
       </div>
    </div>
  </div>
</div>

                             
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
