demo = {
    initPickColor: function () {
        $(".pick-class-label").click(function () {
            var new_class = $(this).attr("new-class");
            var old_class = $("#display-buttons").attr("data-class");
            var display_div = $("#display-buttons");
            if (display_div.length) {
                var display_buttons = display_div.find(".btn");
                display_buttons.removeClass(old_class);
                display_buttons.addClass(new_class);
                display_div.attr("data-class", new_class);
            }
        });
    },

    // initDocChart: function() {
    //   chartColor = "#FFFFFF";

    //   // General configuration for the charts with Line gradientStroke
    //   gradientChartOptionsConfiguration = {
    //     maintainAspectRatio: false,
    //     legend: {
    //       display: false
    //     },
    //     tooltips: {
    //       bodySpacing: 4,
    //       mode: "nearest",
    //       intersect: 0,
    //       position: "nearest",
    //       xPadding: 10,
    //       yPadding: 10,
    //       caretPadding: 10
    //     },
    //     responsive: true,
    //     scales: {
    //       yAxes: [{
    //         display: 0,
    //         gridLines: 0,
    //         ticks: {
    //           display: false
    //         },
    //         gridLines: {
    //           zeroLineColor: "transparent",
    //           drawTicks: false,
    //           display: false,
    //           drawBorder: false
    //         }
    //       }],
    //       xAxes: [{
    //         display: 0,
    //         gridLines: 0,
    //         ticks: {
    //           display: false
    //         },
    //         gridLines: {
    //           zeroLineColor: "transparent",
    //           drawTicks: false,
    //           display: false,
    //           drawBorder: false
    //         }
    //       }]
    //     },
    //     layout: {
    //       padding: {
    //         left: 0,
    //         right: 0,
    //         top: 15,
    //         bottom: 15
    //       }
    //     }
    //   };

    //   ctx = document.getElementById('lineChartExample').getContext("2d");

    //   gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
    //   gradientStroke.addColorStop(0, '#80b6f4');
    //   gradientStroke.addColorStop(1, chartColor);

    //   gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
    //   gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
    //   gradientFill.addColorStop(1, "rgba(249, 99, 59, 0.40)");

    //   myChart = new Chart(ctx, {
    //     type: 'line',
    //     responsive: true,
    //     data: {
    //       labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    //       datasets: [{
    //         label: "Active Users",
    //         borderColor: "#f96332",
    //         pointBorderColor: "#FFF",
    //         pointBackgroundColor: "#f96332",
    //         pointBorderWidth: 2,
    //         pointHoverRadius: 4,
    //         pointHoverBorderWidth: 1,
    //         pointRadius: 4,
    //         fill: true,
    //         backgroundColor: gradientFill,
    //         borderWidth: 2,
    //         data: [542, 480, 430, 550, 530, 453, 380, 434, 568, 610, 700, 630]
    //       }]
    //     },
    //     options: gradientChartOptionsConfiguration
    //   });
    // },

    // ajaxGetPostMonthlyData: function () {
    //   var urlPath =  'http://127.0.0.1:8000/dachboard';
    //   var request = $.ajax( {
    //     method: 'GET',
    //     url: urlPath
    // } );

    //   request.done( function ( response ) {
    //     console.log( response );
    //     charts.createCompletedJobsChart( response );
    //   });
    // },

    initDashboardPageCharts: function (months, post_count_data, max) {
        chartColor = "#FFFFFF";

        // General configuration for the charts with Line gradientStroke
        gradientChartOptionsConfiguration = {
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            tooltips: {
                bodySpacing: 4,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10,
            },
            responsive: 1,
            scales: {
                yAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    },
                ],
                xAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    },
                ],
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 15,
                    bottom: 15,
                },
            },
        };

        gradientChartOptionsConfigurationWithNumbersAndGrid = {
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            tooltips: {
                bodySpacing: 4,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10,
            },
            responsive: true,
            scales: {
                yAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: 0,
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawBorder: false,
                        },
                    },
                ],
                xAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    },
                ],
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 15,
                    bottom: 15,
                },
            },
        };

        var ctx = document.getElementById("bigDashboardChart").getContext("2d");

        var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(0, "#80b6f4");
        gradientStroke.addColorStop(1, chartColor);

        var gradientFill = ctx.createLinearGradient(0, 200, 0, 50);
        gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
        gradientFill.addColorStop(1, "rgba(255, 255, 255, 0.24)");

        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: months,
                datasets: [
                    {
                        label: "Data",
                        borderColor: chartColor,
                        pointBorderColor: chartColor,
                        pointBackgroundColor: "#1e3d60",
                        pointHoverBackgroundColor: "#1e3d60",
                        pointHoverBorderColor: chartColor,
                        pointBorderWidth: 1,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 2,
                        pointRadius: 5,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderWidth: 2,
                        data: post_count_data,
                    },
                ],
            },
            options: {
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 0,
                        bottom: 0,
                    },
                },
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "#fff",
                    titleFontColor: "#333",
                    bodyFontColor: "#666",
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                },
                legend: {
                    position: "bottom",
                    fillStyle: "#FFF",
                    display: false,
                },
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                fontColor: "rgba(255,255,255,0.4)",
                                fontStyle: "bold",
                                beginAtZero: true,
                                maxTicksLimit: max,
                                padding: 10,
                            },
                            gridLines: {
                                drawTicks: true,
                                drawBorder: false,
                                display: true,
                                color: "rgba(255,255,255,0.1)",
                                zeroLineColor: "transparent",
                            },
                        },
                    ],
                    xAxes: [
                        {
                            gridLines: {
                                zeroLineColor: "transparent",
                                display: false,
                            },
                            ticks: {
                                padding: 10,
                                fontColor: "rgba(255,255,255,0.4)",
                                fontStyle: "bold",
                            },
                        },
                    ],
                },
            },
        });

        var cardStatsMiniLineColor = "#fff",
            cardStatsMiniDotColor = "#fff";

        ctx = document.getElementById("lineChartExample").getContext("2d");

        gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(0, "#80b6f4");
        gradientStroke.addColorStop(1, chartColor);

        gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
        gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
        gradientFill.addColorStop(1, "rgba(249, 99, 59, 0.40)");

        myChart = new Chart(ctx, {
            type: "line",
            responsive: true,
            data: {
                labels: nbr_messages_labels,
                datasets: [
                    {
                        label: "nbr de Messages:",
                        borderColor: "#f96332",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#f96332",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderWidth: 2,
                        data: nbr_messages_data,
                    },
                ],
            },
            options: gradientChartOptionsConfiguration,
        });

        ctx = document
            .getElementById("lineChartExampleWithNumbersAndGrid")
            .getContext("2d");

        gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(0, "#18ce0f");
        gradientStroke.addColorStop(1, chartColor);

        gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
        gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
        gradientFill.addColorStop(1, hexToRGB("#18ce0f", 0.4));

        myChart = new Chart(ctx, {
            type: "line",
            responsive: true,
            data: {
                labels: nbr_fichiers_labels,
                datasets: [
                    {
                        label: "nbr de fichiers",
                        borderColor: "#18ce0f",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#18ce0f",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderWidth: 2,
                        data: nbr_fichiers_data,
                    },
                ],
            },
            options: gradientChartOptionsConfigurationWithNumbersAndGrid,
        });

        var e = document
            .getElementById("barChartSimpleGradientsNumbers")
            .getContext("2d");

        gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
        gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
        gradientFill.addColorStop(1, hexToRGB("#2CA8FF", 0.6));

        var a = {
            type: "bar",
            data: {
                labels: nbr_comments_labels,
                datasets: [
                    {
                        label: "Active Countries",
                        backgroundColor: gradientFill,
                        borderColor: "#2CA8FF",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#2CA8FF",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        fill: true,
                        borderWidth: 1,
                        data: nbr_comments_data,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10,
                },
                responsive: 1,
                scales: {
                    yAxes: [
                        {
                            gridLines: 0,
                            ticks: {
                                min: 0,
                                stepSize: 1,
                            },
                            gridLines: {
                                zeroLineColor: "transparent",
                                drawBorder: false,
                            },
                        },
                    ],
                    xAxes: [
                        {
                            display: 0,
                            gridLines: 0,
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                zeroLineColor: "transparent",
                                drawTicks: false,
                                display: false,
                                drawBorder: false,
                            },
                        },
                    ],
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 15,
                        bottom: 15,
                    },
                },
            },
        };

        var viewsChart = new Chart(e, a);

        ctx = document.getElementById("SexeDonutsChart").getContext("2d");
        var options = {
            responsive: true,
            title: {
                display: true,
                position: "top",
                text: " Les utilisateurs Selon leur Sexe : ",
                fontSize: 18,
                fontColor: "#111",
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 16,
                },
            },
        };
        var data1 = {
            labels: ["homme", "femme"],
            datasets: [
                {
                    data: Sexedata,
                    backgroundColor: ["#4ca3dd", "#ffc0cb"],
                    borderColor: ["#CDA776", "#1D7A46"],
                    borderWidth: [1, 1],
                },
            ],
        };
        var chart1 = new Chart(ctx, {
            type: "doughnut",
            data: data1,
            options: options,
        });

        ctx = document.getElementById("UsersTypesDonut").getContext("2d");
        var options2 = {
            responsive: true,
            title: {
                display: true,
                position: "top",
                text: " Les utilisateurs Selon leur types : ",
                fontSize: 18,
                fontColor: "#111",
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 16,
                },
            },
        };
        var data2 = {
            labels: ["Etudiants", "Professeurs"],
            datasets: [
                {
                    data: Typesdata2,
                    backgroundColor: ["#4ca3ad", "#ffc00b"],
                    borderColor: ["#CDA776", "#1D7A46"],
                    borderWidth: [1, 1],
                },
            ],
        };
        var chart1 = new Chart(ctx, {
            type: "doughnut",
            data: data2,
            options: options2,
        });
    },
};
