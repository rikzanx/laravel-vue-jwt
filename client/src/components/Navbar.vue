<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <button class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbar1"
        aria-controls="navbar1"
        aria-expanded="false"
        aria-labelledby="Toogle Navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-md-center" id="navbar1">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link class="nav-link" to="/">Home</router-link>
                </li>
                <li v-if="auth === ''" class="nav-item">
                    <router-link class="nav-link" to="/login">Login</router-link>
                </li>
                <li v-if="auth === ''" class="nav-item">
                    <router-link class="nav-link" to="/register">Register</router-link>
                </li>
                <li v-if="auth === 'true'" class="nav-item">
                    <router-link class="nav-link" to="/profile">Profile</router-link>
                </li>
                <li v-if="auth === 'true'" class="nav-item">
                    <a class="nav-link" @click="Logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import router from '../router'
import { EventBus } from '@/eventBus.js'
export default {
  data () {
    return {
      auth: '',
      user: ''
    }
  },
  methods: {
    Logout () {
      localStorage.removeItem('usertoken')
      router.push({name: 'Home'})
      this.auth = ''
      EventBus.$emit('status_login', '')
      console.log('outtt')
    }
  },
  mounted () {
    if (localStorage.getItem('usertoken') != null) {
      this.auth = 'true'
    } else {
      EventBus.$on('status_login', (status) => {
        this.auth = status
        console.log('gaonok')
      })
    }
  }
}
</script>
