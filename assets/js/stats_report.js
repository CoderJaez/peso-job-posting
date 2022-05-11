$(document).ready(function() {
    // var sectionCounselChartCanvas = $('#section_counsel').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    // var sectionCounselChart = new Chart(sectionCounselChartCanvas);
    weeklyStatsPlacement();
    weeklyStatsReferrals();

    function weeklyStatsReferrals() {
        var weeklyReferralCanvas = $('#weeklyReferral').get(0).getContext('2d');
        $.ajax({
            url: base_url + 'Dashboard/statsWeeklyReferralReport',
            method: 'post',
            success: function(data) {
                var salesChartData = {
                    labels: data.labels,
                    datasets: data.datasets,

                };
                var weeklyReferralsChart = new Chart(weeklyReferralCanvas, {
                    type: 'line',
                    data: salesChartData,
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Weekly referrals'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display: false
                                },
                            }],
                            yAxes: [{
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        if (value % 1 === 0) {
                                            return value;
                                        }
                                    }
                                }
                            }]
                        }
                    }
                });

            }
        });
    }

    function weeklyStatsPlacement() {
        var weeklyPlacementCanvas = $('#weeklyPlacement').get(0).getContext('2d');
        var weeklyPlacementChart = new Chart(weeklyPlacementCanvas);
        $.ajax({
            url: base_url + 'Dashboard/statsWeeklyPlacementReport',
            method: 'post',
            success: function(data) {
                var salesChartData = {
                    labels: data.labels,
                    datasets: data.datasets
                };
                weeklyPlacementChart = new Chart(weeklyPlacementCanvas, {
                    type: 'bar',
                    data: salesChartData,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Weekly placement'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        if (value % 1 === 0) {
                                            return value;
                                        }
                                    }
                                }
                            }]
                        }
                    }
                });
                $.each(data.js, function(key, value) {
                    var progress_grp = '';
                    var width = (value['hired'] / value['vacancy']) * 100;
                    var progress_bar = (width < 30) ? 'progress-bar-red' : ((width > 30 && width < 50) ? 'progress-bar-yellow' : ((width > 50 && width < 70) ? 'progress-bar-aqua' : 'progress-bar-green'));
                    progress_grp += '<div class="progress-group">';
                    progress_grp += '<span class="progress-text">' + value['company'].toUpperCase() + ' </span>';
                    progress_grp += '<span>' + value['position'].toUpperCase() + ' </span>';
                    progress_grp += '<span class="progress-number"><b>' + value['vacancy'] + '</b>/' + value['hired'] + '</span>';
                    progress_grp += '<div class="progress sm">';
                    progress_grp += '<div class="progress-bar ' + progress_bar + '" style="width: ' + width + '%"></div>';
                    progress_grp += '</div>';
                    progress_grp += '</div>';
                    $('#js_status').append(progress_grp);
                })
            }
        })
    }



    //End of Document ready function
});