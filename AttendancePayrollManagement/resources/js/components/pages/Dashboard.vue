<template>
    <v-container>
        <v-row class="top">
            <div class="attendance">
                <v-row class="attendance_sparkline">
                    <v-card
                        id="attend_card"
                        max-width="700"
                        style="background-color:white;"
                    >
                        <v-sheet
                        id="sheet_wrapper"
                        class="v-sheet--offset mx-auto"
                        color="#e1e7ea"
                        elevation="12"

                        max-width="calc(100% - 10px)"
                        >
                        <v-sparkline
                            style="position:absolute"
                            :value="this.absent"
                            color="red"
                            line-width="1"
                            padding="16"
                        ></v-sparkline>
                        <v-sparkline
                            style="position:absolute"
                            :labels="labels"
                            :value="late"
                            color="black"
                            line-width="1"
                            padding="16"
                        ></v-sparkline>
                        <v-sparkline
                            :value="this.leave"
                            color="green"
                            line-width="1"
                            padding="16"
                        ></v-sparkline>
                        </v-sheet>
                        <v-card-text class="_label">
                        <div class="title font-weight-light mb-2">
                            <h3>ATTENDANCE</h3>
                        </div>
                        <v-divider class="my-2"></v-divider>
                        <v-icon
                            class="mr-2"
                            small
                        >
                            mdi-clock
                        </v-icon>
                            <span class="legend"><v-icon style="color:red;">mdi-square</v-icon> Absent </span>
                                <span class="legend"><v-icon style="color:black;">mdi-square</v-icon>Late </span>
                                    <span class="legend"><v-icon style="color:green;">mdi-square</v-icon>Leave </span>
                        </v-card-text>
                    </v-card>
                </v-row>
            </div>

            <div class="employee">
                <Moveable class="moveable "
                v-bind="moveable"
                @drag="handleDrag" >
                <v-container>
                    <v-row justify="space-around">
                    <v-card width="400">
                        <v-img
                        height="200px"
                        src="/assets/emp_background.jpg"
                        >
                        <v-sheet style="background-color:black">
                            <h3 style="color:white;bottom:0;font:bold">
                            TOTAL EMPLOYEES
                        </h3>
                        </v-sheet>
                        <v-card-title>
                            <v-avatar size="85">
                            <img
                                alt="user"
                                src="/assets/account-group.png"
                            >
                            </v-avatar>
                            <router-link to="/admin/employee" exact> <p class="ml-6" id="total"> {{total_employees}}</p> </router-link>
                        </v-card-title>
                        </v-img>

                        <v-card-text>
                        <div>
                            WORDS OF THE DAY
                        </div>

                        <v-timeline
                            align-top
                            dense
                        >
                            <v-timeline-item
                            v-for="message in messages"
                            :key="message.time"
                            :color="message.color"
                            small
                            >
                            <div>
                                <div class="qoute">{{ qoutes }}</div>
                            </div>
                            </v-timeline-item>
                        </v-timeline>
                        </v-card-text>
                    </v-card>
                    </v-row>
                </v-container>
                </Moveable>
            </div>
        </v-row>
            <div class="_calendar">
                    <v-row class="cal">
                        <v-col>
                        <v-sheet height="64">
                            <v-toolbar
                            flat
                            >
                            <v-btn
                                outlined
                                class="mr-4"
                                color="grey darken-2"
                                @click="setToday"
                            >
                                Today
                            </v-btn>
                            <v-btn
                                fab
                                text
                                small
                                color="grey darken-2"
                                @click="prev"
                            >
                                <v-icon small>
                                mdi-chevron-left
                                </v-icon>
                            </v-btn>
                            <v-btn
                                fab
                                text
                                small
                                color="grey darken-2"
                                @click="next"
                            >
                                <v-icon small>
                                mdi-chevron-right
                                </v-icon>
                            </v-btn>
                            <v-toolbar-title v-if="$refs.calendar">
                                {{ $refs.calendar.title }}
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-menu
                                bottom
                                right
                            >
                                <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    outlined
                                    color="grey darken-2"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    <span>{{ typeToLabel[type] }}</span>
                                    <v-icon right>
                                    mdi-menu-down
                                    </v-icon>
                                </v-btn>
                                </template>
                                <v-list>
                                <v-list-item @click="type = 'day'">
                                    <v-list-item-title>Day</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'week'">
                                    <v-list-item-title>Week</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'month'">
                                    <v-list-item-title>Month</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = '4day'">
                                    <v-list-item-title>4 days</v-list-item-title>
                                </v-list-item>
                                </v-list>
                            </v-menu>
                            </v-toolbar>
                        </v-sheet>
                        <v-sheet height="480">
                            <v-calendar
                            ref="calendar"
                            v-model="focus"
                            color="primary"
                            :events="events"
                            :event-color="getEventColor"
                            :type="type"
                            @click:event="showEvent"
                            @click:more="viewDay"
                            @click:date="viewDay"
                            @change="updateRange"
                            ></v-calendar>
                            <v-menu
                            v-model="selectedOpen"
                            :close-on-content-click="false"
                            :activator="selectedElement"
                            offset-x
                            >
                            <v-card
                                color="grey lighten-4"
                                min-width="350px"
                                flat
                            >
                                <v-toolbar
                                :color="selectedEvent.color"
                                dark
                                >
                                <v-btn icon>
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn icon>
                                    <v-icon>mdi-heart</v-icon>
                                </v-btn>
                                <v-btn icon>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>
                                </v-toolbar>
                                <v-card-text>
                                <span v-html="selectedEvent.details"></span>
                                </v-card-text>
                                <v-card-actions>
                                <v-btn
                                    text
                                    color="secondary"
                                    @click="selectedOpen = false"
                                >
                                    Cancel
                                </v-btn>
                                </v-card-actions>
                            </v-card>
                            </v-menu>
                        </v-sheet>
                        </v-col>
                    </v-row>
            </div>
    </v-container>
</template>

<script>
import axios from 'axios'
  export default {
    data(){
      return{
        labels: [
        'JAN',
        'FEB',
        'MAR',
        'APR',
        'MAY',
        'JUNE',
        'JULY',
        'AUG',
        'SEPT',
        'OCT',
        'NOV',
        'DEC',
      ],
        total_employees:'',
        late: [],
        absent: [],
        leave:[],
        focus: '',
        type: 'month',
        typeToLabel: {
            month: 'Month',
            week: 'Week',
            day: 'Day',
            '4day': '4 Days',
        },
        qoutes:'',
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: [],
        messages: [
        {
          message: '',
          color: 'deep-purple lighten-1',
        },
        ],
        moveable: {
            draggable: true,
        }
      }
    },

    mounted(){
        this.get_Attendance()
        this.$refs.calendar.checkChange()
        this.getQoutes()
        this.getTotalEmployee()
    },
    methods: {
        handleDrag({ target, transform }) {
            // console.log('onDrag left, top', transform);
            target.style.transform = transform;
            console.log(target.style)
        },
        get_Attendance(){
            const test1 = [];
            const test2 = [];
            const test3 = [];
            axios.get('/api/get-attendance-status')
            .then(({ data }) => {
            for(var i = 1; i <= 12; i++){
                test1[i-1] = data.late[i]
                test2[i-1] = data.absent[i]
                test3[i-1] = data.leave[i]
            }
            this.late = test1
            this.absent = test2
            this.leave = test3
            })
        },
        getRandomNumberBetween(min=0,max=16){
            return Math.floor(Math.random()*(max-min+1)+min);
        },
        getQoutes(){
            var qoute = []
            axios.get('/api/get-qoutes')
            .then(({ data }) => {
                qoute = data[this.getRandomNumberBetween()]
                this.qoutes = qoute
                console.log(this.qoutes)
            })
        },
        getTotalEmployee(){
            axios.get('/api/get-total-employees')
            .then(({ data }) => {
                this.total_employees = data
            })
        },
        viewDay ({ date }) {
        this.focus = date
        this.type = 'day'
        },
        getEventColor (event) {
        return event.color
        },
        setToday () {
        this.focus = ''
        },
        prev () {
        this.$refs.calendar.prev()
        },
        next () {
        this.$refs.calendar.next()
        },
        showEvent ({ nativeEvent, event }) {
        const open = () => {
            this.selectedEvent = event
            this.selectedElement = nativeEvent.target
            setTimeout(() => this.selectedOpen = true, 10)
        }

        if (this.selectedOpen) {
            this.selectedOpen = false
            setTimeout(open, 10)
        } else {
            open()
        }

        nativeEvent.stopPropagation()
        },
        updateRange ({ start, end }) {
        const events = []

        const min = new Date(`${start.date}T00:00:00`)
        const max = new Date(`${end.date}T23:59:59`)
        const days = (max.getTime() - min.getTime()) / 86400000
        const eventCount = this.rnd(days, days + 20)

        for (let i = 0; i < eventCount; i++) {
            const allDay = this.rnd(0, 3) === 0
            const firstTimestamp = this.rnd(min.getTime(), max.getTime())
            const first = new Date(firstTimestamp - (firstTimestamp % 900000))
            const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
            const second = new Date(first.getTime() + secondTimestamp)

            events.push({
            name: this.names[this.rnd(0, this.names.length - 1)],
            start: first,
            end: second,
            color: this.colors[this.rnd(0, this.colors.length - 1)],
            timed: !allDay,
            })
        }
        this.events = events
        },
        rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
        },
    },
  }
</script>

<style lang="scss" scoped>
#total{
    font-family: 'Share Tech Mono', monospace;
    color: #daf6ff;
    text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0);
    letter-spacing: 0.05em;
    font-size: 72px;
    text-align: left;
    cursor: pointer;
}
.employee{
    max-width: 25%;
    max-height: 25vh;
}
.attendance_sparkline{
    height: auto;
    left: 0;
    padding-left: 15px;
}
#attend_card{
    width: 55%;
}
._label{
    height: auto;
}
.attendance{
    left: 0;
    width: 75%;
    height: 37vh;
    display: block;
}
.employee, .attendance{
    padding: 10px;
}
.top{
    width: 100%;
}
.drag_attendance{
    display:block;
    width:40%;
    color: white;
    left: 0;
    padding-left: 15px;
}
#sheet_wrapper{
height: auto;
}
._calendar{
    width: 40%;
    position: absolute;
    margin-top: -55px;
}
.cal{
    height: 50vh;
}
.v-main__wrap {
    background-color:#2f3136 !important; //light black
    color: #b9bbbe !important;
    .v-card, .v-divider {
        background-color: white !important; //light black
        border: 1px solid #e79303 !important;
        .v-card__title {
            color: #e79303 !important;
            .v-btn {
                background-color: white !important; //dark black
                color: #e79303 !important;
                .v-icon {
                    color: #e79303 !important;
                }
            }
            .v-btn:hover {
                background-color: #e79303 !important;
                color: #fff !important;
                .v-icon {
                    color: #fff !important;
                }
            }
            .v-label, .v-icon, input {
                color: #b9bbbe !important; //lighter black
            }
            .v-input__slot {
                color: #b9bbbe !important; //lighter black
                border-bottom: 1px solid #b9bbbe !important; //lighter black
            }
        }
        .v-data-table {
            background-color:white !important; //light black
            color: #b9bbbe !important; //lighter black
            .v-data-table-header {
                .v-icon, .text-start {
                    color: #e79303 !important;
                }
            }
            .v-icon {
                color: #b9bbbe !important; //lighter black
            }
            .v-icon:hover {
                color: #e79303 !important;
            }
            .v-data-footer {
                .v-select__slot {
                    border-bottom: 1px thin solid #b9bbbe !important; //lighter black
                    .v-select__selection {
                        color: #b9bbbe !important; //lighter black
                    }
                }
            }
        }
    }
}
</style>>
