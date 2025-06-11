<!-- resources/js/Pages/Auth/Login.vue -->
<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <Head title="Login" />

      <div class="w-full max-w-md">
        <!-- Logo Image -->
        <img
          src="/images/DANlogo.svg"
          alt="Dan Europe Logo"
          class="max-w-md mx-auto mb-2"
        />

        <!-- <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Login to your account</h1> -->

        <form @submit.prevent="submit">
          <div class="max-w-md rounded-lg shadow-lg bg-white overflow-hidden">
            <div class="p-6">
              <div class="grid grid-cols-1 gap-y-6">
                <!-- Email -->
                <InputField
                  id="email"
                  name="email"
                  label="Email"
                  type="email"
                  v-model="form.email"
                  autocomplete="email"
                  :error="form.errors.email"
                />

                <!-- Password -->
                <InputField
                  id="password"
                  name="password"
                  label="Password"
                  type="password"
                  v-model="form.password"
                  autocomplete="current-password"
                  :error="form.errors.password"
                />

                <!-- Submit Button e Link -->
                <div class="mt-6">
                  <div class="flex items-center justify-between">
                    <InputField
                      id="remember"
                      name="remember"
                      label="Remember me"
                      element-type="checkbox"
                      v-model="form.remember"
                      :error="form.errors.remember"
                      class="text-slate-600 mb-2"
                    />
                    <div>
                      <p class="text-slate-600 mb-2">
                        Need an account?
                        <Link :href="route('register')" class="text-blue-600 hover:text-blue-900">
                          Register
                        </Link>
                      </p>
                    </div>
                  </div>
                  <button
                    type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-300"
                    :disabled="form.processing"
                  >
                    Login
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script>
  import { useForm, Link } from '@inertiajs/vue3';
  import { Head } from '@inertiajs/vue3';
  import InputField from '../../Components/InputField.vue';

  export default {
    components: {
      Head,
      Link,
      InputField,
    },
    setup() {
      const form = useForm({
        email: '',
        password: '',
        remember: false,
      });

      const submit = () => {
        form.post(route('login'), {
          onSuccess: () => {
            form.reset('password');
            console.log('Login successful');
          },
          onError: () => {
            console.log('Login failed', form.errors);
            form.reset('password');
          },
        });
      };

      return { form, submit };
    },
  };
  </script>
