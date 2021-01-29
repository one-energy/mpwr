import 'alpinejs';
import axios from 'axios';

import Utils from './utils';

import Swal from 'sweetalert2'

require('./default')

window.Utils = new Utils();
window.axios = axios;
window.Swal = Swal
