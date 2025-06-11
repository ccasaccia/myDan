<!-- resources/js/Pages/Auth/Register.vue -->
<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <Head title="Register" />

      <div class="w-full max-w-md">
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Register a new account</h1>

        <form @submit.prevent="submit">
          <div class="max-w-md rounded-lg shadow-lg bg-white overflow-hidden">
            <div class="p-6">
              <div class="grid grid-cols-1 gap-y-6">

                 <!-- Immagine Profilo -->
              <InputField
                id="avatar"
                name="avatar"
                label="Profile Image"
                element-type="file"
                v-model="form.avatar"
                :error="form.errors.avatar"
              />
                <!-- Name -->
                <InputField
                  id="name"
                  name="name"
                  label="Name"
                  v-model="form.name"
                  autocomplete="given-name"
                  :error="form.errors.name"
                />

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
                  autocomplete="new-password"
                  :error="form.errors.password"
                />

                <!-- Confirm Password -->
                <InputField
                  id="password_confirmation"
                  name="password_confirmation"
                  label="Confirm Password"
                  type="password"
                  v-model="form.password_confirmation"
                  autocomplete="new-password"
                  :error="form.errors.password_confirmation"
                />
                <InputField
                    id="role"
                    name="role"
                    label="Role"
                    element-type="select"
                    v-model="form.role"
                    :options="[
                        { value: 'admin', label: 'Admin' },
                        { value: 'user', label: 'User' },
                        { value: 'guest', label: 'Guest' }
                    ]"
                    placeholder="Select a role"
                    :error="form.errors.role"
                    />

                <!-- Submit Button e Link -->
                <div class="mt-6">
                  <div>
                    <p class="text-slate-600 mb-2">
                      Already a user?
                      <Link :href="route('login')" class="text-blue-600 hover:text-blue-900">
                        Login
                      </Link>
                    </p>
                  </div>
                  <button
                    type="submit"
                    class="cursor-pointer w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-300"
                    :disabled="form.processing"
                  >
                    Register
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
      Link,        // Aggiunto per il componente <Link>
      InputField,  // Aggiunto per il componente <InputField>
    },
    setup() {
      const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
        avatar: '',
      });

      const submit = () => {
        form.post(route('register'), {
          onSuccess: () => {
            form.reset('password', 'password_confirmation');
            console.log('Registration successful');
          },
          onError: () => {
            console.log('Registration failed', form.errors);
            form.reset("password", "password_confirmation");
          },
        });
      };

      return { form, submit };
    },
  };
  </script>
