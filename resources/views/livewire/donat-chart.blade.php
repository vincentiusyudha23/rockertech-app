<div>
    <style>
        #percent {
            display: block;
            width: 160px;
            border: 1px solid #CCC;
            border-radius: 5px;
            margin: 50px auto 20px;
            padding: 10px;
            color: #2152ff;
            font-family: 'Lato', Tahoma, Geneva, sans-serif;
            font-size: 35px;
            text-align: center;
        }

        #donut {
            display: block;
            margin: 0px auto;
            color: #2152ff;
            font-size: 20px;
            text-align: center;
        }

        p {
            max-width: 600px;
            margin: 12px auto;
            font-weight: normal;
            font-family: sans-serif;
        }

        code {
            background: #FAFAFA;
            border: 1px solid #DDD;
            border-radius: 3px;
            padding: 0px 4px;
        }

        /* Variabel SCSS diubah ke nilai CSS langsung */
        .donut-size {
            font-size: 12em;
        }

        .pie-wrapper {
            position: relative;
            width: 1em;
            height: 1em;
            margin: 0px auto;
        }

        .pie-wrapper .pie {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            clip: rect(0, 1em, 1em, 0.5em);
        }

        .pie-wrapper .half-circle {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            border: 0.1em solid #2152ff;
            border-radius: 50%;
            clip: rect(0em, 0.5em, 1em, 0em);
        }

        .pie-wrapper .right-side {
            transform: rotate(0deg);
        }

        .pie-wrapper .label {
            position: absolute;
            top: 0.52em;
            right: 0.5em;
            bottom: 0.5em;
            left: 0.5em;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-size: 0.2em;
            background: none;
            border-radius: 50%;
            cursor: default;
            z-index: 2;
        }

        .pie-wrapper .smaller {
            padding-bottom: 20px;
            font-size: .45em;
            vertical-align: super;
        }

        .pie-wrapper .shadow {
            width: 100%;
            height: 100%;
            border: 0.1em solid #BDC3C7;
            border-radius: 50%;
        }
    </style>

    <div id="specificChart" class="donut-size">
        <div class="pie-wrapper">
            <span class="label">
                <p class="p-0 m-0 font-weight-normal text-sm">This Month</p>
                <p style="font-size: 1em;" class="p-0 m-0">
                    <span class="num font-weight-bold">0</span>
                    <span class="font-weight-bold">/ 20</span>
                </p>
            </span>
            <div class="pie">
                <div class="left-side half-circle"></div>
                <div class="right-side half-circle"></div>
            </div>
            <div class="shadow"></div>
        </div>
    </div>

    <!-- ignore the stuff after here -->

    <input type="hidden" id="percent" value="5">
    <label class="d-none" id="donut"><input type="checkbox" checked> <span>Donut</span></label>
</div>

@push('scripts')
    <script>
        /**
         * Updates the donut chart's percent number and the CSS positioning of the progress bar.
         * Also allows you to set if it is a donut or pie chart
         * @param  {string}  el      The selector for the donut to update. '#thing'
         * @param  {number}  percent Passing in 22.3 will make the chart show 22%
         * @param  {boolean} donut   True shows donut, false shows pie
         */
        function updateDonutChart(el, current, max,donut) {
            var percent = Math.round((current / max) * 100);
            if (percent > 100) {
                percent = 100;
            } else if (percent < 0) {
                percent = 0;
            }
            var deg = Math.round(360 * (percent / 100));

            if (percent > 50) {
                $(el + ' .pie').css('clip', 'rect(auto, auto, auto, auto)');
                $(el + ' .right-side').css('transform', 'rotate(180deg)');
            } else {
                $(el + ' .pie').css('clip', 'rect(0, 1em, 1em, 0.5em)');
                $(el + ' .right-side').css('transform', 'rotate(0deg)');
            }
            
            $(el + ' .right-side').css('border-width', '0.1em');
            $(el + ' .left-side').css('border-width', '0.1em');
            $(el + ' .shadow').css('border-width', '0.1em');
            
            $(el + ' .num').text(current);
            $(el + ' .left-side').css('transform', 'rotate(' + deg + 'deg)');
        }

        // Pass in a number for the percent
        updateDonutChart('#specificChart', 20, 20, true);
        //Ignore the rest, it's for the input and checkbox

        $('#percent').change(function() {
            var percent = $(this).val();
            var max = 20;
            var donut = $('#donut input').is(':checked');
            updateDonutChart('#specificChart', percent, max, donut);
        }).keyup(function() {
            var percent = $(this).val();
            var max = 20;
            var donut = $('#donut input').is(':checked');
            updateDonutChart('#specificChart', percent, max,donut);
        });

        $('#donut input').change(function() {
            var donut = $('#donut input').is(':checked');
            var percent = $("#percent").val();
            var max = 20;
            if (donut) {
                $('#donut span').html('Donut');
            } else {
                $('#donut span').html('Pie');
            }
            updateDonutChart('#specificChart', percent,max, donut);
        });
    </script>
@endpush
