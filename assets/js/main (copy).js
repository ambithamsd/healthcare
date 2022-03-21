
var annualLCFFrevenue = 1600000;

var annualLCFF_EPA_Inlieu = 1900000;

var annualRevenueAll = 2000000;

var totalExpenses = 1950000;

var cashOnHand = 100000;

var chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
};




var __previousDeferal;

var __previousNoDeferal;

var minimumCashBalance;
var projectedMaximumFundDeferal;


var __scheduled = ["Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun"];

var __actualDeferalOut = ["Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul2"];

// var __actualDeferrelIn = ["Aug2", "Sep2", "Oct2", "Nov2", "Dec2", "Jan2", "Feb2", "Mar2", "Apr2", "May2", "Jun2", "Jul2"];

var __percentageIncome = ["0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "4.77", "7.38", "7.38", "7.38", "9"];

// var __percentageOut    = ["9","7.38","7.38","7.38","4.77","0.00","0.00","0.00","0.00","0.00","0.00","0.00"];




var __fromActual = ["Aug", "Aug", "Sep", "Sep", "Oct", "Oct", "Nov", "Nov", "Dec", "Dec", "Jan", "Jan", "Feb", "Feb", "Mar", "Mar", "Apr", "Apr", "May", "May", "Jun", "Jun", "Jul2", "Jul2"];

var __toActual = ["", "", "Nov", "", "", "", "", "", "", "", "", "", "", "", "Dec2", "", "Nov2", "", "Oct2", "", "Sep2", "", "Aug2", ""];

var __percentageIncomeIn = ["", "", "0.00", "", "", "", "", "", "", "", "", "", "", "", "4.77", "", "7.38", "", "7.38", "", "7.38", "", "9", ""];

// var __expence = ["Jul-Sep", "Oct-Jul"];

var __expencePercentage = ["30", "7.78"];

var __deferralOut = [];

var __deferralIn = [];

var __expencePattern = [];


var __revenueMonth = ["Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul2", "Aug2", "Sep2", "Oct2", "Nov2", "Dec2", "Jan2", "Feb2", "Mar2", "Apr2", "May2", "Jun2", "Jul2"]


var __revenuAll = [];

var __finalArray = [];

$(document).ready(function () {

    __deferralOut = __scheduled.map((schedule, index) => {
        return {
            "schedule": schedule,
            "actual": __actualDeferalOut[index],
            "amount": Math.round((annualLCFFrevenue * __percentageIncome[index]) / 100),
            "percentage": __percentageIncome[index],
            "percentagePMT": __percentageIncome[index] / .09
        }
    })

    __deferralIn = __fromActual.map((fromActual, index) => {
        return {
            "fromActual": fromActual,
            "toActual": __toActual[index],
            "amount": Math.round((annualLCFFrevenue * __percentageIncomeIn[index]) / 100),
            "percentage": __percentageIncomeIn[index]
        }
    })
    // __expencePattern = __expence.map((expence,index)=>{
    //     return {
    //      "month": expence,
    //      "expence": (totalExpenses*__expencePercentage[index])/100,
    //      "percenatge" :__expencePercentage[index]
    //     }
    // })

    // console.log(__expencePattern);



    __revenuAll = __revenueMonth.map((month, index) => {

        return {
            "month": month,
            "revenue": calculator.revenueCalculation(month),
            "deferredRevenue": calculator.deferredRevenue(month, index),
            "expense": calculator.expenseCalculation(month)
        }

    })

    __finalArray = __revenuAll.map((revenue, index) => {
        return {
            ...revenue,
            "deferral": calculator.deferralCalculation(revenue, index),
            "noDeferral": calculator.noDeferralCalculation(revenue, index)
        }

    })
    __finalArray = [{ "deferral": cashOnHand, "noDeferral": cashOnHand, "month": "Beginning cash", "revenue": null, "deferredRevenue": null, "expense": null }, ...__finalArray];

    //    console.log(__finalArray);

    var minValu = __finalArray.map((value, index) => {
        return value.deferral;

    })


    minimumCashBalance = Math.min.apply(null, __finalArray.map(a => a.deferral));
    projectedMaximumFundDeferal = annualLCFF_EPA_Inlieu * .4;
    console.log(minimumCashBalance, "---minimumCashBalance----");
    console.log(projectedMaximumFundDeferal, "---projectedMaximumFundDeferal----");


    var ctx = document.getElementById('myChart');
    var label, defferal, nodeferral;
    if (__finalArray.length > 0) {
        label = __finalArray.map((data, index) => { return data.month });
        deferral = __finalArray.map((data, index) => { return data.deferral });
        nodeferral = __finalArray.map((data, index) => { return data.noDeferral });
    }
    var config = {
        type: 'line',
        data: {
            // labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            labels: label,
            datasets: [{
                label: 'Deferrals',
                fill: false,
                backgroundColor: chartColors.blue,
                borderColor: chartColors.blue,
                data: deferral

                ,
            }, {
                label: 'Nodeferrals',
                fill: false,
                backgroundColor: chartColors.yellow,
                borderColor: chartColors.yellow,
                borderDash: [5, 5],
                data: nodeferral,
            }

            ],
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Chart.js Line Chart'
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
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            }
        }
    };


    // window.onload = function() {
    var ctx = document.getElementById('myChart').getContext('2d');
    window.myLine = new Chart(ctx, config);
    // };






















})





var calculator = {
    revenueCalculation: function (month) {
        var monthIn = ["Aug", "Sep", "Aug2", "Sep2"];
        var revenue = 0;
        if (monthIn.includes(month) == true) {
            revenue = annualRevenueAll * .05;
        }
        else {
            if (month != "Jul") {

                revenue = annualRevenueAll * .09;
            }
        }
        return revenue;

    },
    expenseCalculation: function (month) {
        var monthIn = ["Jul", "Aug", "Sep", "Jul2", "Aug2", "Sep2"];
        var revenue = 0;
        if (monthIn.includes(month) == true) {
            revenue = totalExpenses * .1;
        }
        else {
            revenue = (totalExpenses * __expencePercentage[1]) / 100;
        }
        return revenue;

    },
    deferredRevenue: function (month, index) {
        var deferred = 0;

        if (index <= 12) {

            __deferralOut.map((deferOut, index) => {
                if (deferOut.actual == month) {
                    deferred = -Math.abs(deferOut.amount);
                }
            })


        }
        else {
            __deferralIn.map((deferIn, index) => {
                if (deferIn.toActual == month) {
                    deferred = deferIn.amount;
                }
            })

        }

        return deferred;

    },
    deferralCalculation: function (data, index) {
        if (index == 0) {
            __previousDeferal = cashOnHand;
        }
        var deferal = (__previousDeferal + data.revenue + data.deferredRevenue) - data.expense;
        __previousDeferal = deferal;
        return deferal;
    },
    noDeferralCalculation: function (data, index) {
        if (index == 0) {
            __previousNoDeferal = cashOnHand;
        }
        var noDeferal = (__previousNoDeferal + data.revenue) - data.expense;
        __previousNoDeferal = noDeferal;
        return noDeferal;
    }

}

