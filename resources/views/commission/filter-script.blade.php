<script>
 $(document).ready(function () {
            $('#filter_page').on('click', async function(event){
                event.preventDefault();
                let data = {
                    from: $('#from_date_order_filter').val(),
                    to: $('#to_date_order_filter').val(),
                    user_id: $('#user_filter').val()
                }      
                axios.post("{{route('commission.report.filter')}}", data)
                        .then(function (response) {
                            if (response.data.success === true) {
                                axiosForFilter(response);
                            }
                            if(response.data.success === false){
                                // console.log(error)
                            }
                        })
                    .catch(function (error) {
                        // console.log(error)
                    })
            });
            function axiosForFilter(response) {         
                $('#commission-body').find('tr').remove();
    
                    let htmlCode = '';

                    if (response.data.totalRecords) {
                    let i = 0;
                    
                    response.data.data.forEach(function(row) {
                        i++;
                       
                        htmlCode += '<tr>';
                        htmlCode += '<td>' + row.invoice + '</td>';
                        htmlCode += '<td>' + row.purchaser + '</td>';
                        htmlCode += '<td>' + row.distributor + '</td>';
                        htmlCode += '<td>' + row.referred_distributor + '</td>';
                        htmlCode += '<td>' + row.order_date + '</td>';
                        htmlCode += '<td>' + row.order_total + '</td>';
                        htmlCode += '<td>' + row.percentage + '</td>';
                        htmlCode += '<td>' + row.commission + '</td>';
                        htmlCode += '<td><a href="/items/viewData/' + row.id+ '" class="btn btn-primary modal_global_filter"><i class="glyphicon glyphicon-eye-open">View Items</i></a></td>';
                        htmlCode += '</tr>';
                    });
                }

                // console.log(response.data.pagination);

                $('#commission-body').html(htmlCode);  
                $('#commission_pagination').remove();
                if (response.data.totalRecords > 10) {
                    $('#commission').append(`<div id="commission_pagination">${response.data.pagination} </div>`)
                }    

                axiosForViewItem()            ;
            }

        $('#commission').on('click', '#commission_pagination .page-link', async function (e){
            e.stopImmediatePropagation();
            let url = $(this).attr('href');

            let filterOptions = {
                        from: $('#from_date_order_filter').val(),
                        to: $('#to_date_order_filter').val(),
                        user_id: $('#user_filter').val()
                    }

            try {
                axios.post(url, filterOptions)
                    .then(function (response) {
                        // console.log(response.data);
                        if (response.data.success === true) {
                            // console.log(response.data);
                            axiosForFilter(response);
                        }
                        if(response.data.success === false){
                            
                        }
                    })
                .catch(function (error) {
                    
                })

            } catch (e) {
                // console.dir(e);
                
            }

            e.preventDefault();
        });

        let axiosForViewItem = () => {
            $('.modal_global_filter').on('click', function(event) {
            event.preventDefault();

            var url = $(this).attr('href');

            $("#modal-global").modal('show');

                    axios.get(url)
                    .then(async function (response) {
                        let htmlCode = '';    
                        let queryData = response.data.responseMessage.data;
                        let invoiceNumber = response.data.responseMessage.invoice_number;
                        if (response) {
                                htmlCode += '<h4 class="">'+ invoiceNumber+'</h4>';
                                htmlCode += '<table class="table table-hover">';
                                htmlCode += '<thead>';
                                htmlCode += '<tr>';
                                htmlCode += ' <th scope="col">SKU</th>';
                                htmlCode += '<th scope="col">Product Name</th>';
                                htmlCode += '<th scope="col">Price</th>';
                                htmlCode += '<th scope="col">Quantity</th>';
                                htmlCode += '<th scope="col">Total</th>';
                                htmlCode += '</tr>';
                                htmlCode += '</thead>';
                                htmlCode += '<tbody>';
                            }

                        if (response) {
                            let i = 0;
                                
                            queryData.forEach(function(row) {
                                i++;
                                htmlCode += '<tr>';
                                htmlCode += '<td>' + row.order_id + '</td>';
                                htmlCode += '<td>' + row.product_name + '</td>';
                                htmlCode += '<td>' + row.product_price + '</td>';
                                htmlCode += '<td>' + row.qantity + '</td>';
                                htmlCode += '<td>' + row.total + '</td>';
                                htmlCode += '</tr>';
                                });
                        }
                        $("#modal-global").find('.modal-body').html(htmlCode);                                
                    })
                });
        }


        
});
</script>
