import Dashboard    from './components/pages/Dashboard'
import Employee     from './components/pages/employee/Employee'
import AddEmployee  from './components/pages/employee/AddEmployee'
import ShowEmployee from './components/pages/employee/ShowEmployee'
import EditEmployee from './components/pages/employee/EditEmployee'
import Attendance   from './components/pages/attendance/Attendance'
import Payroll      from './components/pages/payroll/Payroll'
import Payslip      from './components/pages/payroll/Payslip'
import ShowPayslip  from './components/pages/payroll/ShowPayslip'
import User         from './components/pages/User'
import Expense      from './components/pages/Expense'
import Position     from './components/pages/Position'
import Benefit      from './components/pages/Benefit'
import Schedule     from './components/pages/Schedule'

//lloyd
import RolesAndPermission from './components/pages/RolesAndPermission'
import ChangePass from './components/pages/Changepassword'
import User_Dashboard from './components/user/UserDashboard'
import Request_Leave from './components/user/pages/Requestleave'
import My_leaves from './components/user/pages/Myleaves'
import Request from './components/pages/Request.vue'
import cashAdvance from './components/pages/CashAdvance.vue'
import My_payroll from './components/user/pages/MyPayroll.vue'
export default{
    mode: 'history',
    linkActiveClass: 'font-semibold',
    routes:[
        {
            path:       '/admin/dashboard',
            component:  Dashboard,
            name:       'Dashboard'
        },
        {
            path:       '/admin/employee',
            component:  Employee,
            name:       'Employee'
        },
        {
            path:       '/admin/employee/add',
            component:  AddEmployee,
            name:       'Add-Employee'
        },
        {
            path:       '/admin/employee/show',
            component:  ShowEmployee,
            name:       'Show-Employee'
        },
        {
            path:       '/admin/employee/edit',
            component:  EditEmployee,
            name:       'Edit-Employee'
        },
        {
            path:       '/admin/attendance',
            component:  Attendance,
            name:       'Attendance'
        },
        {
            path:       '/admin/payroll',
            component:  Payroll,
            name:       'Payroll'
        },
        {
            path:       '/admin/payslip',
            component:  Payslip,
            name:       'Payslip'
        },
        {
            path:       '/admin/show',
            component:  ShowPayslip,
            name:       'ShowPayslip'
        },
        {
            path:       '/admin/user',
            component:  User,
            name:       'User'
        },
        {
            path:       '/admin/expense',
            component:  Expense,
            name:       'Expense'
        },
        {
            path:       '/admin/position',
            component:  Position,
            name:       'Position'
        },
        {
            path:       '/admin/benefit',
            component:  Benefit,
            name:       'Benefit'
        },
        {
            path:       '/admin/schedule',
            component:  Schedule,
            name:       'Schedule'
        },
        {
            path: '/admin/roles-and-permission',
            component: RolesAndPermission,
            name: 'RolesAndPermission'
        },
        {
            path:       '/admin/Request',
            component:  Request,
            name:       'Request'
        },
        {
            path:       '/admin/request/cash-advance',
            component:  cashAdvance,
            name:       'Cash_advance'
        },
        // user attendance
        {
            path:       '/user/attendance',
            component:  Attendance,
            name:       'Attendance'
        },
         // user payroll
         {
            path:       '/user/mypayroll',
            component:  My_payroll,
            name:       'My_Payroll'
        },
         // user dashboard
         {
            path:       '/user/dashboard',
            component:  User_Dashboard,
            name:       'User_Dashboard'
        },
        // user request leave
        {
            path:       '/user/request_leave',
            component:  Request_Leave,
            name:       'Request_Leave'
        },
        // user veiw leave
        {
            path:       '/user/myLeaves',
            component:  My_leaves,
            name:       'My_leaves'
        },

        {
            path: '/changepassword',
            component:ChangePass,
            name:'Changepass'
        }
    ]
}