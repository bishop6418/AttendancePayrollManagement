<template>
    <div>
        <v-card outlined elevation="2">
            <v-card-title>
                <!-- Dialog For Adding Attendance at a specific Time & Date -->
                <v-dialog v-model="dialogAttendance1" persistent max-width="600px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                        dark
                        v-bind="attrs"
                        v-on="on"
                        @click="updateTime"
                        >
                        Attendance
                        </v-btn>
                    </template>
                    <v-card id="clock">
                        <v-card-title>
                            <span class="headline"> ATTENDANCE </span>
                            <v-spacer></v-spacer>
                            <v-btn
                            dark
                            @click="close"
                            style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                            text
                            >
                            <v-icon> mdi-close </v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-subtitle class="text-center">
                        <p class="date"> {{ this.date_now }} </p>
                        <p class="time"> {{ this.time_now }} </p>
                        </v-card-subtitle>
                        <v-dialog
                            ref="dialog"
                            v-model="modal"
                            :return-value.sync="date"
                            persistent
                            width="290px"
                            >
                            <v-date-picker v-model="date" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="modal = false">Close</v-btn>
                            <v-btn text color="primary" @click="$refs.dialog.save(date)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                        <v-dialog
                            ref="dialog2"
                            v-model="modal2"
                            :return-value.sync="time"
                            persistent
                            width="290px"
                        >
                            <v-time-picker
                            v-if="modal2"
                            v-model="time"
                            full-width
                            scrollable
                            format="24hr"
                            >
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="modal2 = false">Close</v-btn>
                            <v-btn text color="primary" @click="$refs.dialog2.save(time)">OK</v-btn>
                            </v-time-picker>
                        </v-dialog>
                        <v-card-text class="text-center">
                            <v-container>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-autocomplete
                                        v-model="emp_id"
                                        :items="employees"
                                        :item-value="'id'"
                                        dense
                                        filled
                                        label="Enter Employee Name">
                                        <template slot="selection" slot-scope="data">
                                        {{ data.item.first_name }} {{ data.item.last_name }}
                                        </template>
                                        <template slot="item" slot-scope="data">
                                        {{ data.item.first_name }} {{ data.item.last_name }}
                                        </template>
                                        </v-autocomplete>
                                        <br>
                                        <p class="text"  @click="modal = true">DATE: {{this.date}}</p>
                                        <br>
                                        <p class="text"  @click="modal2 = true">TIME: {{this.time}}</p>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn dark text @click.prevent="_log">Log</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-spacer></v-spacer>

                <!-- SEARCH BAR -->
                <v-col cols="12" sm="6" md="2">
                    <v-text-field
                    v-model="search"
                    label="Search"
                    single-line
                    hide-details
                    >
                    <v-icon
                    slot="append"
                    >
                    mdi-magnify
                    </v-icon>
                    </v-text-field>
                </v-col>
            </v-card-title>
        <!-- Displays Current Month and legends -->
            <v-container>
                <template>
                    <v-toolbar flat color="#efeaea">
                    <v-btn class="btn" outlined fab text small color="grey darken-2" @click="prevMonth">
                        <v-icon small>mdi-chevron-left</v-icon>
                    </v-btn>
                    <v-dialog
                    v-model="modal3"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    persistent
                    width="290px">
                    <v-date-picker type="month" v-model="month_picked" @input="current_month">
                    <v-btn @click="modal3=false"> close </v-btn>
                    </v-date-picker>
                    </v-dialog>
                    <span class="month" style="cursor:pointer" @click="modal3=true"> {{month_dates.month}} {{month_dates.year}} </span>
                    <v-btn class="btn" outlined fab text small color="grey darken-2" @click="nextMonth"> <v-icon small>mdi-chevron-right</v-icon> </v-btn>
                    <v-spacer></v-spacer>
                    <div style="bottom:1px">
                        <span class="legend">Today <v-icon style="color:#00897B;">mdi-square</v-icon></span>
                            <span class="legend">Absent <v-icon style="color:#d9434e;">mdi-square</v-icon></span>
                                <span class="legend">Late <v-icon style="color:#2d2c2b;">mdi-square</v-icon></span>
                                    <span class="legend">Leave <v-icon style="color:#3ab75c;">mdi-square</v-icon></span>
                    </div>
                    </v-toolbar>
                </template>
                <!-- Displays the Monitoring table of attendances -->
                <div class="table_scroll">
                    <div class="css_table">
                        <div class="css_thead">
                            <div class="css_tr" >
                                <div class="css_th">EMPLOYEES</div>
                                    <div class="css_th" v-for="(date, i) in month_dates.day" :key="i"
                                        :style="{background: weekEndsColor(month_dates.week[i], date)}">
                                            {{month_dates.week[i]}} <br> {{date}}
                                    </div>
                                </div>
                            </div>
                        <div class="css_tbody">
                            <div class="css_tr" v-for="employee in attendance" :key="employee.id">
                                <div class="css_sd" data-label="Name" style="z-index:1">{{ employee.first_name }} {{ employee.last_name }}</div>
                                    <div class="css_td" v-for="(date, i) in month_dates.days"
                                    style="position:relative"
                                    :style="{ background:getColor(extractEmployeeData(employee.attendances,date), extractEmployeeData(employee.attendances,date, 'pm'))}"
                                        :key="i">
                                            <div class="diagonal">
                                                <span class="grab_date">{{extractDate(employee.attendances, date)}}</span>
                                                <span class="get_name">{{extract_Id(employee.attendances, date)}}</span>
                                                <v-tooltip class="tooltip_head" left>
                                                    <template v-slot:activator="{ on, attrs }">
                                                    <span v-bind="attrs" v-on="on" class="left" @click="status_Dialog=true" @click.prevent="on_Click_am"> {{extractEmployeeData(employee.attendances,date)}}</span>
                                                    </template>
                                                    <span>{{extractEmployeeData(employee.attendances,date)}}</span>
                                                    </v-tooltip>
                                                    <v-tooltip class="tooltip_head" left>
                                                    <template v-slot:activator="{ on, attrs }">
                                                    <span v-bind="attrs" v-on="on" class="right" @click="status_Dialog=true" @click.prevent="on_Click_pm"> {{extractEmployeeData(employee.attendances,date, 'pm')}} </span>
                                                    <span class="grab_date">{{extractDate(employee.attendances, date)}}</span>
                                                    </template>
                                                    <span>{{extractEmployeeData(employee.attendances,date, 'pm')}}</span>
                                                </v-tooltip>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <v-dialog v-model="status_Dialog" persistent max-width="350px">
                                <v-card id="status_details">
                                    <v-card-title>
                                    ATTENDANCE DETAILS
                                    </v-card-title>
                                    <v-card-text>
                                    <v-container>
                                        <v-row>
                                        <v-col cols="12">
                                            <p style="color:white" class="text"> Employee Name: <br> <span class="emp_name">{{status.name}}</span> </p>
                                        </v-col>
                                        <v-col cols="12">
                                            <p class="text" style="color:white"> Status:
                                            <v-select
                                            :items="statuses"
                                            v-model="status.new_status"
                                            dense>
                                            </v-select>
                                            </p>
                                        </v-col>
                                    <v-col cols="12">
                                            <p style="color:white" class="text"> Date: <br> <span class="att_date"> {{status.start_date}}</span> </p>
                                    </v-col>
                                        </v-row>
                                    </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn outlined color="blue darken-1" text @click="status_Dialog = false">Close</v-btn>
                                    <v-btn outlined color="blue darken-1" text @click="status_Dialog = false" @click.prevent="update">Save</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </div>
                    </div>
            </v-container>
        </v-card>
    </div>
</template>

<script>
import axios from 'axios'
var week = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
var months = ['January,', 'February,', 'March,', 'April,', 'May,', 'June,', 'July,', 'August,', 'September,', 'October,', 'November,', 'December,'];
    export default {
        data () {
        return {
            dialogAttendance1: false,
            dialogAttendance2: false,
            status_Dialog: false,
            search: '',
            emp_id:'',
            emp_status:'',
            curr_month: new Date(),
            counter: 1,
            counter2: 1,
            flag: null,
            modal:false,
            modal2:false,
            modal3:false,
            extracted_id:'',
            month_dates: '',
            day_of_week: '',
            date: new Date().toISOString().substr(0, 10),
            month_picked: null,
            time: '00:00:00',
            date_now:'',
            time_now:'',
            employees:[],
            attendance:[],
            statuses:['on-time','late','absent','on-leave'],
            status: [
                {
                    new_status: '',
                    name: '',
                    start_date: '',
                    end_date: '',
                },
            ],

            }
        },

        mounted(){
            this.getAttendance()
            this.getEmployees()
            this.getDate()
        },

        methods: {
            extractEmployeeData(attendances, date, display = "am") {
                if(attendances.length > 0){
                    let employee_attendance =  attendances.filter(attendance => {
                        return attendance.date == date
                    })
                    if (employee_attendance.length > 0){
                        return display != 'am'
                            ? employee_attendance[0].status_pm
                            : employee_attendance[0].status_am
                    }
                }
                return ''
            },
            extractDate(attendances, date) {
                if(attendances.length > 0){
                    let employee_attendance =  attendances.filter(attendance => {
                        return attendance.date == date
                    })
                    if (employee_attendance.length > 0){
                        return employee_attendance[0].date
                    }
                }
                return ''
            },
            extract_Id(attendances, date) {
                if(attendances.length > 0){
                    let employee_attendance =  attendances.filter(attendance => {
                        return attendance.date == date
                    })
                    if (employee_attendance.length > 0){
                        return employee_attendance[0].employee_id
                    }
                }
                return ''
            },

            close() {
                    this.dialogAttendance1 = false;
                    this.dialogAttendance2 = false;
                    this.dialogLogin = false;
                    this.dialogRegister = false;
                },
            getColor (status_am, status_pm) {
                var color1 = ' '
                var color2 = ' '
                if (status_am == 'on-leave'){
                    color1 = '#3ab75c'
                        if(status_pm == 'on-leave'){
                            color2 = '#3ab75c'
                        }
                        else if(status_pm == 'late'){
                            color2 = '#2d2c2b'
                        }
                        else if(status_pm == 'absent'){
                            color2 = '#d9434e'
                        }
                        else{
                            color2 = '#efeaea'
                        }
                        return `linear-gradient(to bottom right, ${color1} 50%, ${color2} 51%)`
                    }
                else if (status_am == 'late'){
                    color1 = '#2d2c2b'
                    if(status_pm == 'on-leave'){
                        color2 = '#3ab75c'
                    }
                    else if(status_pm == 'late'){
                        color2 = '#2d2c2b'
                    }
                    else if(status_pm == 'absent'){
                        color2 = '#d9434e'
                    }
                    else{
                        color2 = '#efeaea'
                    }
                    return `linear-gradient(to bottom right, ${color1} 50%, ${color2} 51%)`
                }
                else if (status_am == 'absent'){
                    color1 = '#d9434e'
                    if(status_pm == 'on-leave'){
                        color2 = '#3ab75c'
                    }
                    else if(status_pm == 'late'){
                        color2 = '#2d2c2b'
                    }
                    else if(status_pm == 'absent'){
                        color2 = '#d9434e'
                    }
                    else{
                        color2 = '#efeaea'
                    }
                    return `linear-gradient(to bottom right, ${color1} 50%, ${color2} 51%)`
                }
                else{
                    color1 = '#efeaea'
                    if(status_pm == 'on-leave'){
                        color2 = '#3ab75c'
                    }
                    else if(status_pm == 'late'){
                        color2 = '#2d2c2b'
                    }
                    else if(status_pm == 'absent'){
                        color2 = '#d9434e'
                    }
                    else{
                        color2 = '#efeaea'
                    }
                    return `linear-gradient(to bottom right, ${color1} 50%, ${color2} 51%)`
                }
            },
            weekEndsColor(day,date) {
                if(date == this.curr_month.getDate() && months[this.curr_month.getMonth()] == this.month_dates.month && this.curr_month.getFullYear() == this.month_dates.year){
                    return '#00897B'
                }
                else if(day == 'SAT' || day == 'SUN'){
                    return '#202932'
                }
                else{
                    return '#37474F'
                }
            },
            getAttendance() {
                axios.get('/api/get-attendance').then(({ data }) => {
                this.attendance = data;
                })
            },
            getEmployees() {
                axios.get('/api/get-employees').then(({ data }) => {
                this.employees = data;
                })
            },
            getDate() {
                axios.get('/api/get-attendance_date').then(({ data }) => {
                this.month_dates = data
                })
            },
            current_month() {
                var com_date = new Date(this.month_picked)
                const picked_month = {
                    choosed_month: com_date.getMonth() + 1,
                    year: com_date.getFullYear(),
                    ctr3: 3 }
                axios.post('/api/attendance-month', picked_month).then(({ data }) => {
                this.month_dates = data
                })
            },
            nextMonth() {
                this.counter2 += 1
                this.counter += 1
                if(this.month_picked != null){
                    var month_got = new Date(this.month_picked)
                    const next_month = {
                    after_month: month_got.getDate() + this.counter,
                    picked_year2: month_got.getFullYear(),
                    ctr2: 2 }
                axios.post('/api/attendance-month', next_month).then(({ data }) => {
                this.month_dates = data
                })
                }
                else{
                    const next_month = {
                    after_month: this.curr_month.getMonth() + this.counter,
                    picked_year2: null,
                    ctr2: 2 }
                axios.post('/api/attendance-month', next_month).then(({ data }) => {
                this.month_dates = data
                })
                }
            },
            prevMonth() {
                this.counter -= 1
                this.counter2 -= 1
                if(this.month_picked != null){
                    var month_got = new Date(this.month_picked)
                    const prev_month = {
                    before_month: month_got.getDate() + this.counter2,
                    picked_year1: month_got.getFullYear(),
                    ctr1: 1 }
                axios.post('/api/attendance-month', prev_month).then(({ data }) => {
                this.month_dates = data
                })
                }
                else{
                    const prev_month = {
                    before_month: this.curr_month.getMonth() + this.counter2,
                    picked_year1: null,
                    ctr1: 1 }
                axios.post('/api/attendance-month', prev_month).then(({ data }) => {
                this.month_dates = data
                })
                }
            },
            on_Click_am(event){
                var stats = []
                for(var $j = 0; $j < (event.path[0].innerText).length; $j++ ){
                    stats[$j] = event.path[0].innerText[$j]
                }
                this.status.new_status = stats.join('')
                this.status.name = event.path[3].childNodes[0].innerText
                this.status.start_date = event.path[1].childNodes[0].innerText
                this.status.end_date = event.path[1].childNodes[0].innerText
                this.extracted_id = event.path[1].childNodes[2].innerText
                this.flag = 1
            },
            on_Click_pm(event){
                var stats = []
                for(var $j = 0; $j < (event.path[0].innerText).length; $j++ ){
                    stats[$j] = event.path[0].innerText[$j]
                }
                this.status.new_status = stats.join('')
                this.status.name = event.path[3].childNodes[0].innerText
                this.status.start_date = event.path[1].childNodes[0].innerText
                this.status.end_date = event.path[1].childNodes[0].innerText
                this.extracted_id = event.path[1].childNodes[2].innerText
                this.flag = 2
            },
            update(){
                console.log(this.status)
                const stats = {
                    id: this.extracted_id,
                    status: this.status.new_status,
                    date: this.status.start_date,
                    flag: this.flag
                }
                axios.post('/api/update-attendance', stats)
                .then(({ data }) => {
                        (data.message != null) ? alert(data.message) : alert('The time_out you input is invalid. Maybe the employee has no time_in yet')
                })
                this.getAttendance()
            },
            _log(){
                const info =
                        {
                            emp_id:    this.emp_id,
                            date:		    this.date,
                            time:	        this.time
                        }
                axios.post('/api/add-attendance', info)
                    .then(({ data }) => {
                        (data.message != null) ? alert(data.message) : alert('The time_out you input is invalid. Maybe the employee has no time_in yet')
                })
                    this.getAttendance()
            },
            updateTime() {
                var cd = new Date();
                this.time_now = this.zeroPadding(cd.getHours(), 2) + ':' + this.zeroPadding(cd.getMinutes(), 2) + ':' + this.zeroPadding(cd.getSeconds(), 2);
                this.date_now = this.zeroPadding(cd.getFullYear(), 4) + '-' + this.zeroPadding(cd.getMonth()+1, 2) + '-' + this.zeroPadding(cd.getDate(), 2) + ' ' + week[cd.getDay()];
                setInterval(this.updateTime, 1000)
            },
            zeroPadding(num, digit) {
                var zero = '';
                for(var i = 0; i < digit; i++) {
                    zero += '0';
                }
                return (zero + num).slice(-digit);
            }
        }
    }
</script>

<style lang="scss" scope>
#clock {
    .v-card__title {
        font-family: 'Share Tech Mono', monospace;
        color: #C62828;
        text-shadow: 0 0 20px rgb(230, 43, 10),  0 0 20px rgba(10, 175, 230, 0);
        .v-icon {
            color: #C62828 !important;
        }
    }
    .v-card__subtitle {
        font-family: 'Share Tech Mono', monospace;
        color: #daf6ff;
        text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
        .time {
            letter-spacing: 0.05em;
            font-size: 20px;
        }
        .date {
            letter-spacing: 0.1em;
            font-size: 20px;
        }
    }
    .v-card__text {
        .text {
            font-family: 'Share Tech Mono', monospace;
            color: #339fa0;
            text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
            letter-spacing: 0.05em;
            font-size: 24px;
            text-align: left;
            cursor: pointer;
        }
        input {
            color: #339fa0;
        }
        element.style, .v-text-field {
            color: white;
        }
        .v-label {
            color: #339fa0;
            font-family: 'Share Tech Mono', monospace;
            text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
        }
        fieldset {
            border-color: #ffb600;
        }
        p {
            margin: 0;
            padding: 0;
        }
    }
    .v-select .v-select__selection--comma {
    color: rgb(27 214 162 / 87%);
    }
}
.theme--light.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled) {
    color: rgba(255, 255, 255, 0.952)!important;
}
.month{
    font-size: 24px;
    padding-left:5px;
    padding-right:5px;
}
// STATUS DIALOG
#status_details {
    .v-card__title {
        font-family: 'Share Tech Mono', monospace;
        color: white;
        font-size: 28px;
        text-align: center;
        text-shadow: 0 0 20px rgb(230, 43, 10),  0 0 20px rgba(10, 175, 230, 0);
        .v-icon {
            color: #C62828 !important;
        }
    }
    .v-card__subtitle {
        font-family: 'Share Tech Mono', monospace;
        color: white;
        text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);

    }
    .v-card__text {
        .text {
            font-family: 'Share Tech Mono', monospace;
            color: white;
            text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
            letter-spacing: 0.05em;
            font-size: 20px;
        }
        .emp_name, .att_date{
            font-size: 16px;
            color: turquoise;
        }
        input {
            color: turquoise;
        }
        element.style, .v-text-field {
            color: white;
        }
        .v-label {
            color: white;
            font-family: 'Share Tech Mono', monospace;
            text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
        }
        fieldset {
            border-color: #ffb600;
        }
        p {
            margin: 0;
            padding: 0;
        }
    }
    .v-select .v-select__selection--comma {
    color: rgb(27 214 162 / 87%);
    }
}

/*Table css*/
.table_scroll {
  height: 50%;
  overflow: auto;
  resize: vertical;
  margin:auto;
  max-width:100%;
  box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0.2 );
}
/*Div Table Setting*/
.css_table {
  display: table;
  position: relative;
  width:100%;
}
.css_tr {
  display: table-row;
}
.css_tr:nth-child(even) .css_td {
  background: #f5f5f5;
}
.css_tr:nth-child(odd) .css_td {
  background: #fff;
}
.css_td,
.css_th,
.css_sd{
  display: table-cell;
  text-align: center;
  padding: 8px 10px;
  white-space:nowrap;
  border-bottom: 0.2px solid #fff;
  border-right: 0.2px solid #fff;
}
.css_thead {
  display: table-header-group;
  font-weight: bold;
  z-index:1;
}
.css_tfoot {
  background-color: #eee;
  display: table-footer-group;
  font-weight: bold;
}
.css_tbody {
  display: table-row-group;
}
/*Table Header Fixed */
.css_th, .css_sd{
  position: sticky;
  background-color: #202932;
  color: #fff;
}
.css_th{
  top: 0;
}
.css_sd{
  left: 0;
}
.css_thead div.css_th:first-child,
.css_tfoot div.css_th:first-child{
  left:0;
  z-index:1;
}
.css_tfoot .css_th{
  bottom:0
}
.diagonal{
  width: 25px;
  height: 12.5px;
  padding: 0;
  margin: 0;
}
.diagonal>div {
  height: 25%;
  width: 25%;
  top: 0;
  left: 0;
}
.right{
  position: absolute;
  bottom: 1px;
  right: 1px;
  font-size: 12px;
  color: transparent;
  cursor: pointer;
}
.left{
  position: absolute;
  top: 1px;
  left: 1px;
  font-size: 12px;
  color: transparent;
  cursor: pointer;
}
.grab_date, .get_name{
    color: transparent;
    font-size: 1px;
    cursor: pointer;
}
/*Responsive Div Table*/
@media screen and (max-width: 650px) {
  .css_table {
    border: 0;
  }
//   .css_table .css_thead ,.css_table .css_tfoot{
//     display: none;
//   }
//   .css_table .css_tr {
//     margin-bottom: 10px;
//     display: block;
//     border-bottom: 2px solid #ddd;
//   }
//   .css_table .css_td ,.css_table .css_sd {
//     display: block;
//     text-align: right;
//     font-size: 13px;
//     border-bottom: 1px dotted #ccc;
//     border-left: 1px solid #ddd;
//   }
//   .css_table .css_td:last-child {
//     border-bottom: 0;
//   }
//   .css_table .css_td:before,.css_table .css_sd:before {
//     content: attr(data-label);
//     float: left;
//     text-transform: uppercase;
//     font-weight: bold;
//   }
  .css_sd{
      font-size: 12px;
      width: 15%;
  }
  .css_thead{
      font-size: 12px;
      width: 15%;
  }
  .table_scroll{
    margin:0;
    height:auto!important;
  }
  .css_tr:nth-child(even) .css_td {
  background: #fff;
  }
//   Status details responsive
#status_details {
    .v-card__title {
        font-family: 'Share Tech Mono', monospace;
        color: white;
        font-size: 22px;
        text-align: center;
        text-shadow: 0 0 20px rgb(230, 43, 10),  0 0 20px rgba(10, 175, 230, 0);
        .v-icon {
            color: #C62828 !important;
        }
    }
    .v-card__subtitle {
        font-family: 'Share Tech Mono', monospace;
        color: white;
        text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
    }
    .v-card__text {
        .text {
            font-family: 'Share Tech Mono', monospace;
            color: white;
            text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
            letter-spacing: 0.05em;
            font-size: 20px;
        }
        .emp_name, .att_date{
            font-size: 16px;
            color: turquoise;
        }
        input {
            color: turquoise;
        }
        element.style, .v-text-field {
            color: white;
        }
        .v-label {
            color: white;
            font-family: 'Share Tech Mono', monospace;
            text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
        }
        fieldset {
            border-color: #ffb600;
        }
        p {
            margin: 0;
            padding: 0;
        }
    }
    .v-select .v-select__selection--comma {
    color: rgb(27 214 162 / 87%);
    }
}
.month{
    font-size: 15px;
}
.btn {
    .v-btn--fab.v-size--small {
    height: 30px;
    width: 30px;
    }
}
.legend{
    font-size: 12px;
    bottom: 1px;
}
.tooltip, .tooltip_parent, .v-tooltip__content{
    visibility: hidden;
}

}
</style>
