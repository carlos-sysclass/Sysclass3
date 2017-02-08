{extends file="layout/default.tpl"}
{block name="content"}
<div class="form-body" id="tab-report-show">
	<div class="backgrid-table">
		<table class="table table-striped table-bordered table-hover table-full-width data-table" id="report-datatable">
			<thead></thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<script type="text/template" id="dynamic-table-header-item-template">
	<th class="<%= field.sClass %> <% if (field.sType) { %> <%= field.sType %> <% } %>">
    	<% if (!field.label) { %>
        	<%= field.mData %>
        <% } else { %>
			<%= field.label %>
        <% } %>
    </th>
</script>

{/block}
