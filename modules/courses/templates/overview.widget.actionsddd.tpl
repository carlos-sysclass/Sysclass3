   <div class="btn-group">
      <a data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="#" class="btn default btn-xs">
            Choose..
         <i class="icon-angle-down"></i>
      </a>
      <ul class="dropdown-menu pull-right" id="courses-filter-list">
         <li role="presentation" class="dropdown-header">My Courses</li>
      </ul>
   </div>

   
<script type="text/template" id="course-filter-list-template">
   <li data-course-id="<%= id %>"><a href="#"><%= name %></a></li>
</script>