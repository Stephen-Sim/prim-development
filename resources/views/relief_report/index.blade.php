@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/css/required-asterick.css')}}" rel="stylesheet">
{{-- <link href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"> --}}
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/datatable.css')}}">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
        }

        @media only screen and (max-width: 760px) {
            .btn {
                width: 100%;
            }
            #lrTeacherChart {
                height: 350px !important;
            }
        }

        @media print {
            @page {
                margin: 0.1cm; /* Set the minimum margin value */
            }

            body {
                margin: 0;
                padding: 0;
                /* size: A4 portrait; */
            }
            #lrTeacherChart {
                width: 100% !important;
                height: 350px !important;
                max-width: 100%;
            }
        }
    </style>
@include('layouts.datatable')
@endsection

@section('content')
<div class="row align-items-center">
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4 class="font-size-18">Relief Report</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">

            {{csrf_field()}}
            <div class="card-body">

                <div class="form-group">
                    <label>Organization Name:</label>
                    <select name="organization" id="organization" class="form-control">
                        <option value="" selected disabled>Choose Organization</option>
                        @foreach($organization as $row)
                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- <div class="form-group">
                    <label>Tarikh</label>
                    <input type="text" value="" class="form-control" name="pickup_date" id="datepicker"  placeholder="Pilih tarikh" readonly required>
                </div> -->

                <div class="form-group">
                        <label>Start Date:</label>
                        <input type="text" value="" class="form-control" name="pickup_date" id="datepicker_start"  placeholder="Pilih tarikh" readonly required>
                        <label>End Date:</label>
                        <input type="text" value="" class="form-control" name="pickup_date" id="datepicker_end"  placeholder="Pilih tarikh" readonly required>
                    </div>

                <div>
                    <label>Report Type: </label>
                    <div>
                    <button type="button" id="chart" name="chart" class="btn btn-primary">Leave and Relief Status (Default)</button>
                    <button type="button" id="lr_teacher" name="lr_teacher" class="btn btn-primary">Leave and Relief Teacher</button>
                    <button type="button" class="btn btn-success" onclick="window.print();">Print</button>
                    </div>
                </div>
                
            </div>

            {{-- <div class="">
                <button onclick="filter()" style="float: right" type="submit" class="btn btn-primary"><i
                        class="fa fa-search"></i>
                    Tapis</button>
            </div> --}}

        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                @if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
                @endif
                @if(\Session::has('error'))
                <div class="alert alert-danger">
                    <p>{{ \Session::get('error') }}</p>
                </div>
                @endif

                <div class="flash-message"></div>

                <div id="btn-details">
                <button type="button" id="details" name="details" class="btn btn-primary">Show Details</button>
                </div>
                <div id="chart-section">
                <div class="total_report" style="padding: 10px;">
                    <!-- <div class="total_confirmed"></div>
                    <div class="total_pending"></div>
                    <div class="total_rejected"></div> -->
                    
                    <canvas id="barChart" width="600" height="350"></canvas>
                </div>
            </div>

            <div id="details-section">
                <div class="table-responsive">
                    <table id="reliefTable" class="table table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr style="text-align:center">
                                <th>No </th>
                                <th>Date</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Slot</th>
                                <th>Original Teacher</th>
                                <th>Substitute Teacher</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>        
            
                <div id="lr-teacher-section" style="display: none;">
                    <div class="form-group">
                        <label>Select Teacher:</label>
                        <input type="text" name="select_teacher" id="select_teacher" class="form-control">
                    </div>
                    <p></p>
                    <div class="table-responsive">
                    <table id="teacherTable" class="table table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr style="text-align:center">
                                <th>No </th>
                                <th>Teacher Name</th>
                                <th>Total Slots (Including Original Slots)</th>
                                <th>Relief Taken</th>
                                <th>Remaining Relief Slots</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                    <div class="total_report" style="padding: 10px;">
                        <canvas id="lrTeacherChart" width="600" height="350"></canvas>
                    </div>
                </div>

               
            </div>
        </div>

    </div>
</div>


@endsection


@section('script')
<!-- Peity chart-->
<script src="{{ URL::asset('assets/libs/peity/peity.min.js')}}"></script>

<!-- Plugin Js-->
<script src="{{ URL::asset('assets/libs/chartist/chartist.min.js')}}"></script>

<script src="{{ URL::asset('assets/js/pages/dashboard.init.js')}}"></script>

<script>   
        var dates = []
    $(document).ready(function() {
        $("#datepicker_start").datepicker("setDate", new Date());
        $("#datepicker_end").datepicker("setDate", new Date());

        $("#datepicker_start").datepicker({
            onSelect: function(selectedDate) {
                // Set the minimum date for datepicker_end
                $("#datepicker_end").datepicker("option", "minDate", selectedDate);
                
            }
        });

        // Set the initial minDate for datepicker_end based on the default value of datepicker_start
        $("#datepicker_end").datepicker("option", "minDate", $("#datepicker_start").val());

        dateOnChange();

        if ($("#organization").val() != "") {
            $("#organization").prop("selectedIndex", 1).trigger('change');
            // fetch_data($("#organization").val());
        }

        $('#organization').change(function() {
            var organizationid = $("#organization option:selected").val();
            $('#reliefTable').DataTable().destroy();
            // console.log(organizationid);
            // fetch_data(organizationid);
        });

        // csrf token for ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.alert').delay(3000).fadeOut();

        var delayTimer; // Variable to store the timer ID
        var isDelayActive = false;
        $('#select_teacher').on('change', function () {
            if (!isDelayActive) {
                displayTeacher();

                // Set the delay and update the flag
                isDelayActive = true;
                delayTimer = setTimeout(function () {
                    isDelayActive = false;
                    displayTeacher();
                }, 1000); // 2000 milliseconds (2 seconds)
            }
         
        });

        $('#datepicker_start').change(function() {
        //    dateOnChange();
        $("#datepicker_start").datepicker({
            onSelect: function(selectedDate) {
                // Set the minimum date for datepicker_end
                $("#datepicker_end").datepicker("option", "minDate", selectedDate);
                
            }
        });

        // Set the initial minDate for datepicker_end based on the default value of datepicker_start
        $("#datepicker_end").datepicker("option", "minDate", $("#datepicker_start").val());

        fetchReliefData($('#datepicker_start').val(), $('#datepicker_end').val());
        console.log($('#datepicker_start').val(), $('#datepicker_end').val());

        })

        // Initial fetch when the page loads
        fetchReliefData($('#datepicker_start').val(), $('#datepicker_end').val());
        console.log($('#datepicker_start').val(), $('#datepicker_end').val());

        });
        // end document ready

        $("#datepicker_start").datepicker({
            minDate: '-1m',
            maxDate: '+1m',
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Ahd', 'Isn', 'Sel', 'Rab', 'Kha', 'Jum', 'Sab'],
            beforeShowDay: editDays,
            defaultDate: 0, 
        });

        $("#datepicker_end").datepicker({
            dateFormat: 'yy-mm-dd',
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            onSelect: function(selectedDate) {
                // Trigger the change event when a date is selected
                $('#datepicker_end').trigger('change');
            }
        });

        // function fetchReliefData() {
        //     let date_val = $('#datepicker_start').val();
        //     // If date is empty, set it to today
        //     if (!date_val) {
        //         date_val = $.datepicker.formatDate('yy-mm-dd', new Date());
        //         $('#datepicker_start').datepicker('setDate', date_val); // Update datepicker value
        //     }
        //     console.log(date_val);
        //     $.ajax({
        //         url: '{{ route("schedule.getReliefReport") }}',
        //         type: 'POST',
        //         data: {
        //             organization: $('#organization option:selected').val(), 
        //             date: date_val,
        //             // Replace with your organization ID
        //         },
        //         success: function (response) {
        //             console.log(response); // Log the pending relief data
        //             //console.log(response.available_teachers); // Log the available teachers data 
        //             displayRelief(response.relief_report);
        //         },
        //         error: function (xhr, status, error) {
        //             console.error(error);
        //         }
        //     });
        // }

        $('#datepicker_end').change(function() {
            // Additional logic when datepicker_end changes
            // You can add any custom logic here
            fetchReliefData($('#datepicker_start').val(), $(this).val());
            console.log($('#datepicker_start').val(), $('#datepicker_end').val());
        });

        function fetchReliefData(start_date, end_date) {
            if (end_date === null) {
                // If date_end is null, set end_date to start_date
                end_date = start_date;
            }
        $.ajax({
            url: '{{ route("schedule.getReliefReport") }}',
            type: 'POST',
            data: {
                organization: $('#organization option:selected').val(),
                start_date: start_date,
                end_date: end_date,
            },
            success: function (response) {
                console.log(response); // Log the pending relief data
                displayRelief(response.relief_report);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

    function notifyTeacher(leave_relief_id) {
    // Get the route URL with the placeholder
            var routeUrl = '{{ route("schedule.notifyTeacher", "lrid") }}';
            
            // Replace the placeholder with the leave_relief_id
            var url = routeUrl.replace('lrid', leave_relief_id);
            
            // Make the AJAX call with the modified URL
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    alert(response.message);
                    $('#datepicker_end').trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
        function displayRelief(reliefData) {
            var tableBody = $('#reliefTable tbody');
            tableBody.empty(); // Clear existing data

            // Initialize counters for each status
            var totalConfirmed = 0;
            var totalPending = 0;
            var totalRejected = 0;
            var totalNotAssign = 0;

            // Iterate through reliefData and append rows
            reliefData.forEach(function (relief, index) {
                var row = $('<tr></tr>');
                row.data('relief-id', relief.leave_relief_id);
                row.append('<td>' + (index + 1) + '</td>');
                row.append('<td>' + relief.date + '</td>');
                row.append('<td>' + relief.class_name + '</td>');
                row.append('<td>' + relief.subject + '</td>');
                row.append('<td>' + relief.slot + '</td>');
                row.append('<td>' + relief.leave_teacher + '</td>');
                //row.append('<td>' + relief.relief_teacher + '</td>');

                if (relief.relief_teacher === null) {
                    row.append('<td>Not Assign</td>');
                } else {
                    row.append('<td>' + relief.relief_teacher + '</td>');
                }

                // Set color based on status
                var statusColor;
                var confirmationText;

                switch (relief.confirmation) {
                    case 'Rejected':
                        statusColor = 'red';
                        totalRejected++; // Increment rejected count
                        confirmationText = 'Rejected';
                        break;
                    case 'Confirmed':
                        statusColor = 'green';
                        totalConfirmed++; // Increment confirmed count
                        confirmationText = 'Confirmed';
                        break;
                    case 'Pending':
                        statusColor = 'orange'; // Change 'yellow' to 'orange'
                        totalPending++; // Increment pending count
                        confirmationText = 'Pending';
                        break;
                    default:
                        statusColor = 'black';
                        totalNotAssign++ // Default color for unknown status
                        confirmationText = '-';
                        break;
                }

                row.append('<td id="confirmation_text" style="color: ' + statusColor + ';">' + (relief.confirmation || confirmationText) + '</td>');

                // Inside the forEach loop where buttons are appended
                row.append('<td id="action">');
                if (confirmationText === 'Pending') {
                    row.find('#action').append('<button class="btn btn-success confirm-btn">Confirm</button>');
                    row.find('#action').append('<button class="btn btn-danger reject-btn">Reject</button>');
                }
                if(relief.notification_count <=0){
                    row.find('#action').append('<button class="btn btn-primary noti-btn" onclick=notifyTeacher("'+relief.leave_relief_id +'")>Notify</button>');
                }
                row.append('</td>');

                tableBody.append(row);
            });

            // Update the total blocks with the counts
            // $('.total_confirmed').text('Total Confirmed: ' + totalConfirmed);
            // $('.total_pending').text('Total Pending: ' + totalPending);
            // $('.total_rejected').text('Total Rejected: ' + totalRejected);

            // Update the bar chart
            updateBarChart(totalConfirmed, totalPending, totalRejected, totalNotAssign);
        }

        var barChart; // Declare the chart variable globally

        function updateBarChart(confirmed, pending, rejected, notAssign) {
            var total = confirmed + pending + rejected + notAssign;
            var ctx = document.getElementById('barChart').getContext('2d');

            // Destroy the existing chart if it exists
            if (barChart) {
                barChart.destroy();
            }

            barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Confirmed', 'Pending', 'Rejected', 'Not Assign'],
                    datasets: [{
                        label: 'Total Report (' + total + ')',
                        data: [confirmed, pending, rejected, notAssign],
                        // data: [1, 2, 3, 4],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(169, 169, 169, 0.2)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(169, 169, 169, 1)', 
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                    height: 350
                }
            });
        }


        function autoSuggest(){
        var organization = $("#organization option:selected").val();
        var pendingRelief = ['1-1','2-2']; //get from each row and format it, 'leave_relief_id-schedule_subject_id'
        var criteria = 'class_in_week'; //drop down select
       
        $.ajax({
                url: "{{route('schedule.autoSuggestRelief')}}",
                type: 'POST',
                data: {
                    organization: organization, 
                    pendingRelief: pendingRelief, 
                    criteria: criteria
                },
                success: function(response) {
                    console.log(response); //update the combo box that select the teacher
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function dateOnChange() {
        let date_val = $('#datepicker_start').val(), timePicker = $('#timepicker'), timeRange = $('.time-range')
        let org_id = $('#organization option:selected').val()
        // console.log(date_val)
        if(date_val != '') {
            $('.pickup-time-div').removeAttr('hidden')
        } else {
            $('.pickup-time-div').attr('hidden', true)
        }
        }

    var disabledDates = dates
    
    function editDays(date) {
      for (var i = 0; i < disabledDates.length; i++) {
        if (new Date(disabledDates[i]).toString() == date.toString()) {             
          return [false];
        }
      }
      return [true];
    }

    var displayMode = 'chart';

        // Function to switch between 'details' and 'lr_teacher' modes
        function switchDisplayMode(mode) {
            if (mode === 'details') {
                $('#btn-details').show();
                $('#chart-section').hide();
                $('#details-section').show();
                $('#lr-teacher-section').hide();
            } else if (mode === 'chart') {
                $('#btn-details').show();
                $('#chart-section').show();
                $('#details-section').hide();
                $('#lr-teacher-section').hide();
            } else if (mode === 'lr_teacher') {
                $('#btn-details').hide();
                $('#chart-section').hide();
                $('#details-section').hide();
                $('#lr-teacher-section').show();
                displayTeacher();
            }
        }

        // Initial setup
        switchDisplayMode(displayMode);

        $('#chart').click(function() {
            displayMode = 'chart';
            switchDisplayMode(displayMode);
        });

        // Event handler for 'details' button
        $('#details').click(function() {
            // Toggle the text of the button based on the current mode
            if (displayMode === 'details') {
                displayMode = 'chart';
                $(this).text('Show Details');
            } else {
                displayMode = 'details';
                $(this).text('Show Chart');
            }

            switchDisplayMode(displayMode);
        });

        // Event handler for 'lr_teacher' button
        $('#lr_teacher').click(function() {
            displayMode = 'lr_teacher';
            switchDisplayMode(displayMode);
            displayTeacher();
        });
        
        function fetchTeacher(){
            
        }

        function displayTeacher() {
            var teacher_name = $('#select_teacher').val();
            $.ajax({
            url: '{{ route("schedule.getTeacherSlot") }}',
            type: 'POST',
            data: {
                organization: $('#organization option:selected').val(),
                start_date: $('#datepicker_start').val(),
                end_date: $('#datepicker_end').val(),
                teacher_name:teacher_name
            },
            success: function (response) {
                console.log(response); 
               // Log the pending relief data
                var table = $('#teacherTable tbody');
                table.empty();
               
                // Create a new row using the newRowData
                $('#lr-teacher-section p').text('Result for '+response.NumberOfWeek+' week');
                response.teachers.forEach(function(teacher,i){
                    var newRow = $('<tr>');
                    var fullSlot =teacher.maxSlot*response.NumberOfWeek;
                    // Assuming you have some data properties in newRowData
                    var busy = Math.max(teacher.normal_class*response.NumberOfWeek + teacher.relief_class - teacher.leave_class,0);
                    var remaining_relief = Math.min(teacher.maxRelief- teacher.relief_class, teacher.maxSlot - busy);
                    newRow.append($('<td>').text(i+1));
                    newRow.append($('<td>').text(teacher.name));
                    newRow.append($('<td>').text(busy));
                    newRow.append($('<td>').text(teacher.relief_class));
                    newRow.append($('<td>').text(Math.max(remaining_relief,0)));

                    // Add more columns as needed

                    // Append the new row to the table
                    table.append(newRow);
                });
              
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
        }

        function adminManageRelief(reliefId, confirmationStatus) {
            $.ajax({
                url: '{{ route("schedule.adminManageRelief") }}',
                type: 'POST',
                data: {
                    relief_id: reliefId,
                    confirmation_status: confirmationStatus
                },
                success: function (response) {
                    console.log(response);
                    // Update the table row based on the response
                    if (response.success) {
                        // Find the row with the corresponding relief ID
                        var row = $('#reliefTable tbody').find('tr[data-relief-id="' + reliefId + '"]');
                        // Update the confirmation text and style
                        row.find('#confirmation_text').text(confirmationStatus).css('color', response.color);
                        // Remove the action buttons
                        row.find('#action').empty();
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Event listener for Confirm button
        $(document).on('click', '.confirm-btn', function() {
            var reliefId = $(this).closest('tr').data('relief-id');
            console.log(reliefId);
            adminManageRelief(reliefId, 'Confirmed');
            fetchReliefData($('#datepicker_start').val(), $('#datepicker_end').val());
        });

        // Event listener for Reject button
        $(document).on('click', '.reject-btn', function() {
            var reliefId = $(this).closest('tr').data('relief-id');
            console.log(reliefId);
            adminManageRelief(reliefId, 'Rejected');
            fetchReliefData($('#datepicker_start').val(), $('#datepicker_end').val());
        });


</script>
@endsection