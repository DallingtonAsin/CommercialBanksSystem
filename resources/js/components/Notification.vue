<template>

  <div class="dropdown">
    <a class="btn main-color-bg text-white dropdown"
    id="dropdownmenulink" data-toggle="dropdown">
    <i class="glyphicon glyphicon-globe"></i> 
    Notifications
    <span class="badge bg-danger text-white">
    {{ message.length }} </span> 
  

  </a>
  <div class="dropdown-menu notification-menu" role="menu" aria-labelledby="dropdownmenulink">
    <li v-for="notification in message">
     <small>
      <a href="#" class="text-decoration-none">
       New notification
      {{ JSON.parse(notification.data).action }} <span class="text-danger">read more</span>
    </a>
    </small>
   </li>
          <li v-if="message.length == 0">
              No notification yet      
            </li>

          </div>

        </div>
      </template>

      <script>
        export default {
          // props: [
          // 'notifications',
          // ],
          mounted(){
            axios.post('notification/get').then(response => {
              this.message = response.data;
            });
          },

          data: function(){
            return {
               message: null,
            }
          },
        // methods: {
        //   MarkAsRead: function(){
        //     var data = {
        //       id: notification.id
        //     };
        //     axios.post('notification/read ', data).then(response =>{
        //     // window.location.href = "/post"+notification.data;
        //     }); 
        //   }
        // }
      }
    </script>
