<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mt-5 mx-auto">
                <form v-on:submit.prevent="Login">
                    <h1 class="h3 mb-3 font-wight-normal">Please Sign in</h1>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" v-model="email" class="form-control" name="email" placeholder="Email Address">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" v-model="password" class="form-control" name="password" placeholder="Password">
                    </div>

                    <button class="btn btn-lg btn-block btn-primary">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import router from '../router'
import { EventBus } from '@/eventBus.js'

export default {
  data () {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    Login () {
      axios.post('/api/login', {
        email: this.email,
        password: this.password
      }).then((res) => {
        localStorage.setItem('usertoken', res.data.token)
        this.email = ''
        this.password = ''
        EventBus.$emit('status_login', 'true')
        router.push({name: 'Profile'})
      }).catch((err) => {
        console.log(err)
      })
    },
    emitMethod () {
      EventBus.$emit('status_login', 'true')
    }
  }
}
</script>
