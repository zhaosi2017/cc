$(function () {
    var $companyList = $("#company-list");
    var $table = $('#company-list-table');


    var params  = {
        "_csrf": $table.attr("data-token"),
        "name" : "name",
        "type" : $companyList.attr("data-type"),
        "pageNum" : parseInt($table.attr("data-page-number"))
    };
    var operateEvents = {
        'click .disable': function (e,value,row,index) {
            var pdata = {
                "name" : $(e.toElement).attr("data-name"),
                "status" : row.status==0 ? 1 : 0,
                "_csrf": params._csrf
            };

            $.post("disable",pdata).done(function (r) {
                layer.msg(r.msg);
                $table.bootstrapTable('updateRow', {index: index, row: r.data});
            });
        }
    };

    var columns = [
        {
            field : 'Number',
            title : '序号',
            align : 'center',
            formatter : function(value, row, index) {
                return index + 1;
            }
        },
        {
            field : 'name',
            title : '公司名称',
            align : 'center',
            searchable: true
        },
        {
            field : 'superior_company_name',
            title : '上级公司',
            align : 'center',
            searchable: true
        },
        {
            field : 'status',
            title : '状态',
            align : 'center',
            formatter: function (value) {
                return value==0 ? "正常" : "已作废";
            }
        },
        {
            field : 'level',
            title : '层级',
            align : 'center'
        },
        {
            field : 'create_time',
            title : '创建人／时间',
            align : 'center',
            formatter : function(value, row) {
                return (row.creater ? row.creater : '管理员') + '<br>' + value;
            }
        },
        {
            field : 'update_time',
            title : '最后修改人／时间',
            align : 'center',
            formatter : function(value, row) {
                return (row.updater ? row.updater : '管理员') + '<br>' + value;
            }
        },
        {
            field : 'operators',
            title : '操作',
            events: operateEvents,
            align : 'center',
            formatter : function(value, row) {
                var editHtml,opHtml;
                if(params.type){
                    editHtml = '<a class="edit" href="edit?name='+row.name+'" >编辑</a> ';
                    if(row.status==0){
                        opHtml   = '<a class="disable" data-name="'+row.name+'" style="color:red;">作废</a>';
                    }else{
                        opHtml   = '<a style="color: rgb(186, 186, 186);" data-name="'+row.name+'">作废</a>';
                    }
                }else{
                    editHtml = '';
                    if(row.status==1){
                        opHtml   = '<a class="disable" data-name="'+row.name+'">恢复</a>';
                    }else{
                        opHtml   = '<a style="color: rgb(186, 186, 186);" data-name="'+row.name+'">恢复</a>';
                    }
                }
                return  editHtml + opHtml;
            }
        }
    ];

    var options = {
        method: 'post',
        contentType: "application/x-www-form-urlencoded",
        sidePagination:'server',
        dataField: 'data',
        search: true,
        pagination: true,
        iconSize: 'outline',
        paginationPreText: '上一页',
        paginationNextText: '下一页',
        pageNumber: params.pageNum,
        pageSize: 10,                       //每页的记录行数（*）
        pageList: [10, 50, 100],        //可供选择的每页的行数（*）
        striped: false,                     //是否显示行间隔色
        showRefresh : true,
        escape: true,                       //转义
        columns : columns,
        showToggle: true,
        sortOrder: "desc",
        searchText: "",
        searchOnEnterKey: true,
        trimOnSearch: true,
        useCurrentPage: true,
        queryParams: function (p) {
            p.pageNum = $companyList.find(".pagination li.page-number.active").children("a").html();
            p.name = $companyList.find(".fixed-table-toolbar #searchName").val();
            return $.extend(params, p);
        }

    };

    $table.bootstrapTable(options);
    var selectHtml = '<div class="pull-right">' +
        '<select class="form-control search" id="searchName">' +
        '<option value="name">公司名称</option>' +
        '<option value="superior_company_name">上级公司</option>' +
        '</select></div>';
    $companyList.find(".fixed-table-toolbar").append(selectHtml);

});
