<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/', ['as' => 'home','uses' =>'HomeController@index'])->middleware(['XSS']);
Route::get('/home', ['as' => 'home','uses' =>'HomeController@index'])->middleware(['auth','XSS']);



Route::get('/register/{lang?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register');

Route::get('/login/{lang?}', 'Auth\LoginController@showLoginForm')->name('login');

Route::get('/password/resets/{lang?}', 'Auth\LoginController@showLinkRequestForm')->name('change.langPass');

Route::prefix('customer')->as('customer.')->group(
    function (){
        Route::get('login/{lang}', 'Auth\LoginController@showCustomerLoginLang')->name('login.lang')->middleware(['XSS']);
        Route::get('login', 'Auth\LoginController@showCustomerLoginForm')->name('login')->middleware(['XSS']);
        Route::post('login', 'Auth\LoginController@customerLogin')->name('login')->middleware(['XSS']);

        Route::get('/password/resets/{lang?}', 'Auth\LoginController@showCustomerLinkRequestForm')->name('change.langPass');
        Route::post('/password/email', 'Auth\LoginController@postCustomerEmail')->name('password.email');

        Route::get('reset-password/{token}', 'Auth\LoginController@getCustomerPassword')->name('reset.password');
        Route::post('reset-password', 'Auth\LoginController@updateCustomerPassword')->name('password.update');

        Route::get('dashboard', 'CustomerController@dashboard')->name('dashboard')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );

        Route::get('invoice', 'InvoiceController@customerInvoice')->name('invoice')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('proposal', 'ProposalController@customerProposal')->name('proposal')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );

        Route::get('proposal/{id}/show', 'ProposalController@customerProposalShow')->name('proposal.show')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );

        Route::get('invoice/{id}/send', 'InvoiceController@customerInvoiceSend')->name('invoice.send')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('invoice/{id}/send/mail', 'InvoiceController@customerInvoiceSendMail')->name('invoice.send.mail')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );

        Route::get('invoice/{id}/show', 'InvoiceController@customerInvoiceShow')->name('invoice.show');



        Route::get('payment', 'CustomerController@payment')->name('payment')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('transaction', 'CustomerController@transaction')->name('transaction')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('logout', 'CustomerController@customerLogout')->name('logout')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('profile', 'CustomerController@profile')->name('profile')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );

        Route::post('update-profile', 'CustomerController@editprofile')->name('update.profile')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('billing-info', 'CustomerController@editBilling')->name('update.billing.info')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('shipping-info', 'CustomerController@editShipping')->name('update.shipping.info')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('change.password', 'CustomerController@updatePassword')->name('update.password')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('change-language/{lang}', 'CustomerController@changeLanquage')->name('change.language')->middleware(
            [
                'auth:customer',
                'XSS',
                'revalidate',
            ]
        );


    }
);

Route::prefix('vender')->as('vender.')->group(
    function (){
        Route::get('login/{lang}', 'Auth\LoginController@showVenderLoginLang')->name('login.lang')->middleware(['XSS']);
        Route::get('login', 'Auth\LoginController@showVenderLoginForm')->name('login')->middleware(['XSS']);
        Route::post('login', 'Auth\LoginController@VenderLogin')->name('login')->middleware(['XSS']);

        Route::get('/password/resets/{lang?}', 'Auth\LoginController@showVendorLinkRequestForm')->name('change.langPass');
        Route::post('/password/email', 'Auth\LoginController@postVendorEmail')->name('password.email');

        Route::get('reset-password/{token}', 'Auth\LoginController@getVendorPassword')->name('reset.password');
        Route::post('reset-password', 'Auth\LoginController@updateVendorPassword')->name('password.update');

        Route::get('dashboard', 'VenderController@dashboard')->name('dashboard')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('bill', 'BillController@VenderBill')->name('bill')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('bill/{id}/show', 'BillController@venderBillShow')->name('bill.show')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );


        Route::get('bill/{id}/send', 'BillController@venderBillSend')->name('bill.send')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('bill/{id}/send/mail', 'BillController@venderBillSendMail')->name('bill.send.mail')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );


        Route::get('payment', 'VenderController@payment')->name('payment')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('transaction', 'VenderController@transaction')->name('transaction')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('logout', 'VenderController@venderLogout')->name('logout')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );

        Route::get('profile', 'VenderController@profile')->name('profile')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );

        Route::post('update-profile', 'VenderController@editprofile')->name('update.profile')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('billing-info', 'VenderController@editBilling')->name('update.billing.info')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('shipping-info', 'VenderController@editShipping')->name('update.shipping.info')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::post('change.password', 'VenderController@updatePassword')->name('update.password')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );
        Route::get('change-language/{lang}', 'VenderController@changeLanquage')->name('change.language')->middleware(
            [
                'auth:vender',
                'XSS',
                'revalidate',
            ]
        );

    }
);


Route::get('/', 'DashboardController@account_dashboard_index')->name('dashboard')->middleware(
    [
        'XSS',
        'revalidate',
    ]
);

Route::get('/account-dashboard', 'DashboardController@account_dashboard_index')->name('dashboard')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::get('/project-dashboard', 'DashboardController@project_dashboard_index')->name('project.dashboard')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::get('/hrm-dashboard', 'DashboardController@hrm_dashboard_index')->name('hrm.dashboard')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::get('profile', 'UserController@profile')->name('profile')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::post('edit-profile', 'UserController@editprofile')->name('update.account')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('users', 'UserController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::post('change-password', 'UserController@updatePassword')->name('update.password');


Route::resource('roles', 'RoleController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('permissions', 'PermissionController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('change-language/{lang}', 'LanguageController@changeLanquage')->name('change.language');
    Route::get('manage-language/{lang}', 'LanguageController@manageLanguage')->name('manage.language');
    Route::post('store-language-data/{lang}', 'LanguageController@storeLanguageData')->name('store.language.data');
    Route::get('create-language', 'LanguageController@createLanguage')->name('create.language');
    Route::post('store-language', 'LanguageController@storeLanguage')->name('store.language');

    Route::delete('/lang/{lang}', 'LanguageController@destroyLang')->name('lang.destroy');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::resource('systems', 'SystemController');
    Route::post('email-settings', 'SystemController@saveEmailSettings')->name('email.settings');
    Route::post('company-settings', 'SystemController@saveCompanySettings')->name('company.settings');
    Route::post('system-settings', 'SystemController@saveSystemSettings')->name('system.settings');
    Route::get('print-setting', 'SystemController@printIndex')->name('print.setting');
    Route::get('company-setting', 'SystemController@companyIndex')->name('company.setting');
    Route::post('business-setting', 'SystemController@saveBusinessSettings')->name('business.setting');
    Route::post('company-payment-setting', 'SystemController@saveCompanyPaymentSettings')->name('company.payment.settings');
    Route::get('test-mail', 'SystemController@testMail')->name('test.mail');
    Route::post('test-mail', 'SystemController@testSendMail')->name('test.send.mail');
    Route::post('stripe-settings', 'SystemController@savePaymentSettings')->name('payment.settings');

    Route::post('pusher-setting', 'SystemController@savePusherSettings')->name('pusher.setting');
}
);

Route::get('productservice/index', 'ProductServiceController@index')->name('productservice.index');
Route::resource('productservice', 'ProductServiceController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('customer/{id}/show', 'CustomerController@show')->name('customer.show');
    Route::resource('customer', 'CustomerController');

}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('vender/{id}/show', 'VenderController@show')->name('vender.show');
    Route::resource('vender', 'VenderController');

}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::resource('bank-account', 'BankAccountController');

}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('bank-transfer/index', 'BankTransferController@index')->name('bank-transfer.index');
    Route::resource('bank-transfer', 'BankTransferController');

}
);


Route::resource('taxes', 'TaxController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('product-category', 'ProductServiceCategoryController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('product-unit', 'ProductServiceUnitController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);


Route::get('invoice/pdf/{id}', 'InvoiceController@invoice')->name('invoice.pdf')->middleware(
    [
        'XSS',
        'revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){


    Route::get('invoice/{id}/duplicate', 'InvoiceController@duplicate')->name('invoice.duplicate');
    Route::get('invoice/{id}/shipping/print', 'InvoiceController@shippingDisplay')->name('invoice.shipping.print');
    Route::get('invoice/{id}/payment/reminder', 'InvoiceController@paymentReminder')->name('invoice.payment.reminder');
    Route::get('invoice/index', 'InvoiceController@index')->name('invoice.index');
    Route::post('invoice/product/destroy', 'InvoiceController@productDestroy')->name('invoice.product.destroy');
    Route::post('invoice/product', 'InvoiceController@product')->name('invoice.product');
    Route::post('invoice/customer', 'InvoiceController@customer')->name('invoice.customer');
    Route::get('invoice/{id}/sent', 'InvoiceController@sent')->name('invoice.sent');
    Route::get('invoice/{id}/resent', 'InvoiceController@resent')->name('invoice.resent');
    Route::get('invoice/{id}/payment', 'InvoiceController@payment')->name('invoice.payment');
    Route::post('invoice/{id}/payment', 'InvoiceController@createPayment')->name('invoice.payment');
    Route::post('invoice/{id}/payment/{pid}/destroy', 'InvoiceController@paymentDestroy')->name('invoice.payment.destroy');
    Route::get('invoice/items', 'InvoiceController@items')->name('invoice.items');

    Route::resource('invoice', 'InvoiceController');
    Route::get('invoice/create/{cid}', 'InvoiceController@create')->name('invoice.create');
}
);

Route::get(
    '/invoices/preview/{template}/{color}', [
                                              'as' => 'invoice.preview',
                                              'uses' => 'InvoiceController@previewInvoice',
                                          ]
);
Route::post(
    '/invoices/template/setting', [
                                    'as' => 'template.setting',
                                    'uses' => 'InvoiceController@saveTemplateSettings',
                                ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){


    Route::get('credit-note', 'CreditNoteController@index')->name('credit.note');
    Route::get('custom-credit-note', 'CreditNoteController@customCreate')->name('invoice.custom.credit.note');
    Route::post('custom-credit-note', 'CreditNoteController@customStore')->name('invoice.custom.credit.note');
    Route::get('credit-note/invoice', 'CreditNoteController@getinvoice')->name('invoice.get');
    Route::get('invoice/{id}/credit-note', 'CreditNoteController@create')->name('invoice.credit.note');
    Route::post('invoice/{id}/credit-note', 'CreditNoteController@store')->name('invoice.credit.note');
    Route::get('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@edit')->name('invoice.edit.credit.note');
    Route::post('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@update')->name('invoice.edit.credit.note');
    Route::delete('invoice/{id}/credit-note/delete/{cn_id}', 'CreditNoteController@destroy')->name('invoice.delete.credit.note');

}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){


    Route::get('debit-note', 'DebitNoteController@index')->name('debit.note');
    Route::get('custom-debit-note', 'DebitNoteController@customCreate')->name('bill.custom.debit.note');
    Route::post('custom-debit-note', 'DebitNoteController@customStore')->name('bill.custom.debit.note');
    Route::get('debit-note/bill', 'DebitNoteController@getbill')->name('bill.get');
    Route::get('bill/{id}/debit-note', 'DebitNoteController@create')->name('bill.debit.note');
    Route::post('bill/{id}/debit-note', 'DebitNoteController@store')->name('bill.debit.note');
    Route::get('bill/{id}/debit-note/edit/{cn_id}', 'DebitNoteController@edit')->name('bill.edit.debit.note');
    Route::post('bill/{id}/debit-note/edit/{cn_id}', 'DebitNoteController@update')->name('bill.edit.debit.note');
    Route::delete('bill/{id}/debit-note/delete/{cn_id}', 'DebitNoteController@destroy')->name('bill.delete.debit.note');

}
);


Route::get(
    '/bill/preview/{template}/{color}', [
                                          'as' => 'bill.preview',
                                          'uses' => 'BillController@previewBill',
                                      ]
);
Route::post(
    '/bill/template/setting', [
                                'as' => 'bill.template.setting',
                                'uses' => 'BillController@saveBillTemplateSettings',
                            ]
);

Route::resource('taxes', 'TaxController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::get('revenue/index', 'RevenueController@index')->name('revenue.index')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('revenue', 'RevenueController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::get('bill/pdf/{id}', 'BillController@bill')->name('bill.pdf')->middleware(
    [
        'XSS',
        'revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('bill/{id}/duplicate', 'BillController@duplicate')->name('bill.duplicate');
    Route::get('bill/{id}/shipping/print', 'BillController@shippingDisplay')->name('bill.shipping.print');
    Route::get('bill/index', 'BillController@index')->name('bill.index');
    Route::post('bill/product/destroy', 'BillController@productDestroy')->name('bill.product.destroy');
    Route::post('bill/product', 'BillController@product')->name('bill.product');
    Route::post('bill/vender', 'BillController@vender')->name('bill.vender');
    Route::get('bill/{id}/sent', 'BillController@sent')->name('bill.sent');
    Route::get('bill/{id}/resent', 'BillController@resent')->name('bill.resent');
    Route::get('bill/{id}/payment', 'BillController@payment')->name('bill.payment');
    Route::post('bill/{id}/payment', 'BillController@createPayment')->name('bill.payment');
    Route::post('bill/{id}/payment/{pid}/destroy', 'BillController@paymentDestroy')->name('bill.payment.destroy');
    Route::get('bill/items', 'BillController@items')->name('bill.items');

    Route::resource('bill', 'BillController');
    Route::get('bill/create/{cid}', 'BillController@create')->name('bill.create');
}
);


Route::get('payment/index', 'PaymentController@index')->name('payment.index')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('payment', 'PaymentController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('report/transaction', 'TransactionController@index')->name('transaction.index');


}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('report/income-summary', 'ReportController@incomeSummary')->name('report.income.summary');
    Route::get('report/expense-summary', 'ReportController@expenseSummary')->name('report.expense.summary');
    Route::get('report/income-vs-expense-summary', 'ReportController@incomeVsExpenseSummary')->name('report.income.vs.expense.summary');
    Route::get('report/tax-summary', 'ReportController@taxSummary')->name('report.tax.summary');
    Route::get('report/profit-loss-summary', 'ReportController@profitLossSummary')->name('report.profit.loss.summary');

    Route::get('report/invoice-summary', 'ReportController@invoiceSummary')->name('report.invoice.summary');
    Route::get('report/bill-summary', 'ReportController@billSummary')->name('report.bill.summary');

    Route::get('report/invoice-report', 'ReportController@invoiceReport')->name('report.invoice');
    Route::get('report/account-statement-report', 'ReportController@accountStatement')->name('report.account.statement');

    Route::get('report/balance-sheet', 'ReportController@balanceSheet')->name('report.balance.sheet');
    Route::get('report/ledger', 'ReportController@ledgerSummary')->name('report.ledger');
    Route::get('report/trial-balance', 'ReportController@trialBalanceSummary')->name('trial.balance');
}
);


Route::get('proposal/pdf/{id}', 'ProposalController@proposal')->name('proposal.pdf')->middleware(
    [
        'XSS',
        'revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('proposal/{id}/status/change', 'ProposalController@statusChange')->name('proposal.status.change');
    Route::get('proposal/{id}/convert', 'ProposalController@convert')->name('proposal.convert');
    Route::get('proposal/{id}/duplicate', 'ProposalController@duplicate')->name('proposal.duplicate');
    Route::post('proposal/product/destroy', 'ProposalController@productDestroy')->name('proposal.product.destroy');
    Route::post('proposal/customer', 'ProposalController@customer')->name('proposal.customer');
    Route::post('proposal/product', 'ProposalController@product')->name('proposal.product');
    Route::get('proposal/items', 'ProposalController@items')->name('proposal.items');
    Route::get('proposal/{id}/sent', 'ProposalController@sent')->name('proposal.sent');
    Route::get('proposal/{id}/resent', 'ProposalController@resent')->name('proposal.resent');

    Route::resource('proposal', 'ProposalController');
    Route::get('proposal/create/{cid}', 'ProposalController@create')->name('proposal.create');
}
);

Route::get(
    '/proposal/preview/{template}/{color}', [
                                              'as' => 'proposal.preview',
                                              'uses' => 'ProposalController@previewProposal',
                                          ]
);
Route::post(
    '/proposal/template/setting', [
                                    'as' => 'proposal.template.setting',
                                    'uses' => 'ProposalController@saveProposalTemplateSettings',
                                ]
);

Route::resource('goal', 'GoalController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('account-assets', 'AssetController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('custom-field', 'CustomFieldController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::post('chart-of-account/subtype', 'ChartOfAccountController@getSubType')->name('charofAccount.subType')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::resource('chart-of-account', 'ChartOfAccountController');

}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::post('journal-entry/account/destroy', 'JournalEntryController@accountDestroy')->name('journal.account.destroy');
    Route::resource('journal-entry', 'JournalEntryController');

}
);

// Client Module
Route::resource('clients', 'ClientController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
// Deal Module
Route::post(
    '/deals/user', [
    'as' => 'deal.user.json',
    'uses' => 'DealController@jsonUser',
]
);
Route::post(
    '/deals/order', [
    'as' => 'deals.order',
    'uses' => 'DealController@order',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/change-pipeline', [
    'as' => 'deals.change.pipeline',
    'uses' => 'DealController@changePipeline',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/change-deal-status/{id}', [
    'as' => 'deals.change.status',
    'uses' => 'DealController@changeStatus',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/labels', [
    'as' => 'deals.labels',
    'uses' => 'DealController@labels',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/{id}/labels', [
    'as' => 'deals.labels.store',
    'uses' => 'DealController@labelStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/users', [
    'as' => 'deals.users.edit',
    'uses' => 'DealController@userEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/users', [
    'as' => 'deals.users.update',
    'uses' => 'DealController@userUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/deals/{id}/users/{uid}', [
    'as' => 'deals.users.destroy',
    'uses' => 'DealController@userDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/clients', [
    'as' => 'deals.clients.edit',
    'uses' => 'DealController@clientEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/clients', [
    'as' => 'deals.clients.update',
    'uses' => 'DealController@clientUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/deals/{id}/clients/{uid}', [
    'as' => 'deals.clients.destroy',
    'uses' => 'DealController@clientDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/products', [
    'as' => 'deals.products.edit',
    'uses' => 'DealController@productEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/products', [
    'as' => 'deals.products.update',
    'uses' => 'DealController@productUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/deals/{id}/products/{uid}', [
    'as' => 'deals.products.destroy',
    'uses' => 'DealController@productDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/sources', [
    'as' => 'deals.sources.edit',
    'uses' => 'DealController@sourceEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/sources', [
    'as' => 'deals.sources.update',
    'uses' => 'DealController@sourceUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/deals/{id}/sources/{uid}', [
    'as' => 'deals.sources.destroy',
    'uses' => 'DealController@sourceDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/{id}/file', [
    'as' => 'deals.file.upload',
    'uses' => 'DealController@fileUpload',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/file/{fid}', [
    'as' => 'deals.file.download',
    'uses' => 'DealController@fileDownload',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/deals/{id}/file/delete/{fid}', [
    'as' => 'deals.file.delete',
    'uses' => 'DealController@fileDelete',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/{id}/note', [
    'as' => 'deals.note.store',
    'uses' => 'DealController@noteStore',
]
)->middleware(['auth']);
Route::get(
    '/deals/{id}/task', [
    'as' => 'deals.tasks.create',
    'uses' => 'DealController@taskCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/{id}/task', [
    'as' => 'deals.tasks.store',
    'uses' => 'DealController@taskStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/task/{tid}/show', [
    'as' => 'deals.tasks.show',
    'uses' => 'DealController@taskShow',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/task/{tid}/edit', [
    'as' => 'deals.tasks.edit',
    'uses' => 'DealController@taskEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/task/{tid}', [
    'as' => 'deals.tasks.update',
    'uses' => 'DealController@taskUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/task_status/{tid}', [
    'as' => 'deals.tasks.update_status',
    'uses' => 'DealController@taskUpdateStatus',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/deals/{id}/task/{tid}', [
    'as' => 'deals.tasks.destroy',
    'uses' => 'DealController@taskDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/discussions', [
    'as' => 'deals.discussions.create',
    'uses' => 'DealController@discussionCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/{id}/discussions', [
    'as' => 'deals.discussion.store',
    'uses' => 'DealController@discussionStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/{id}/permission/{cid}', [
    'as' => 'deals.client.permission',
    'uses' => 'DealController@permission',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/permission/{cid}', [
    'as' => 'deals.client.permissions.store',
    'uses' => 'DealController@permissionStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/deals/list', [
    'as' => 'deals.list',
    'uses' => 'DealController@deal_list',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Deal Calls
Route::get(
    '/deals/{id}/call', [
    'as' => 'deals.calls.create',
    'uses' => 'DealController@callCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/{id}/call', [
    'as' => 'deals.calls.store',
    'uses' => 'DealController@callStore',
]
)->middleware(['auth']);
Route::get(
    '/deals/{id}/call/{cid}/edit', [
    'as' => 'deals.calls.edit',
    'uses' => 'DealController@callEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/deals/{id}/call/{cid}', [
    'as' => 'deals.calls.update',
    'uses' => 'DealController@callUpdate',
]
)->middleware(['auth']);
Route::delete(
    '/deals/{id}/call/{cid}', [
    'as' => 'deals.calls.destroy',
    'uses' => 'DealController@callDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Deal Email
Route::get(
    '/deals/{id}/email', [
    'as' => 'deals.emails.create',
    'uses' => 'DealController@emailCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/deals/{id}/email', [
    'as' => 'deals.emails.store',
    'uses' => 'DealController@emailStore',
]
)->middleware(['auth']);
Route::resource('deals', 'DealController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
// end Deal Module

Route::get(
    '/search', [
    'as' => 'search.json',
    'uses' => 'UserController@search',
]
);
Route::post(
    '/stages/order', [
    'as' => 'stages.order',
    'uses' => 'StageController@order',
]
);
Route::post(
    '/stages/json', [
    'as' => 'stages.json',
    'uses' => 'StageController@json',
]
);

Route::resource('stages', 'StageController');
Route::resource('pipelines', 'PipelineController');
Route::resource('labels', 'LabelController');
Route::resource('sources', 'SourceController');
Route::resource('payments', 'PaymentController');
Route::resource('custom_fields', 'CustomFieldController');

// Leads Module
Route::post(
    '/lead_stages/order', [
    'as' => 'lead_stages.order',
    'uses' => 'LeadStageController@order',
]
);
Route::resource('lead_stages', 'LeadStageController')->middleware(['auth']);
Route::post(
    '/leads/json', [
    'as' => 'leads.json',
    'uses' => 'LeadController@json',
]
);
Route::post(
    '/leads/order', [
    'as' => 'leads.order',
    'uses' => 'LeadController@order',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/leads/list', [
    'as' => 'leads.list',
    'uses' => 'LeadController@lead_list',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/leads/{id}/file', [
    'as' => 'leads.file.upload',
    'uses' => 'LeadController@fileUpload',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/leads/{id}/file/{fid}', [
    'as' => 'leads.file.download',
    'uses' => 'LeadController@fileDownload',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/leads/{id}/file/delete/{fid}', [
    'as' => 'leads.file.delete',
    'uses' => 'LeadController@fileDelete',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/leads/{id}/note', [
    'as' => 'leads.note.store',
    'uses' => 'LeadController@noteStore',
]
)->middleware(['auth']);
Route::get(
    '/leads/{id}/labels', [
    'as' => 'leads.labels',
    'uses' => 'LeadController@labels',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/leads/{id}/labels', [
    'as' => 'leads.labels.store',
    'uses' => 'LeadController@labelStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/leads/{id}/users', [
    'as' => 'leads.users.edit',
    'uses' => 'LeadController@userEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/leads/{id}/users', [
    'as' => 'leads.users.update',
    'uses' => 'LeadController@userUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/leads/{id}/users/{uid}', [
    'as' => 'leads.users.destroy',
    'uses' => 'LeadController@userDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/leads/{id}/products', [
    'as' => 'leads.products.edit',
    'uses' => 'LeadController@productEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/leads/{id}/products', [
    'as' => 'leads.products.update',
    'uses' => 'LeadController@productUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/leads/{id}/products/{uid}', [
    'as' => 'leads.products.destroy',
    'uses' => 'LeadController@productDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/leads/{id}/sources', [
    'as' => 'leads.sources.edit',
    'uses' => 'LeadController@sourceEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/leads/{id}/sources', [
    'as' => 'leads.sources.update',
    'uses' => 'LeadController@sourceUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/leads/{id}/sources/{uid}', [
    'as' => 'leads.sources.destroy',
    'uses' => 'LeadController@sourceDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/leads/{id}/discussions', [
    'as' => 'leads.discussions.create',
    'uses' => 'LeadController@discussionCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/leads/{id}/discussions', [
    'as' => 'leads.discussion.store',
    'uses' => 'LeadController@discussionStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/leads/{id}/show_convert', [
    'as' => 'leads.convert.deal',
    'uses' => 'LeadController@showConvertToDeal',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/leads/{id}/convert', [
    'as' => 'leads.convert.to.deal',
    'uses' => 'LeadController@convertToDeal',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Lead Calls
Route::get(
    '/leads/{id}/call', [
    'as' => 'leads.calls.create',
    'uses' => 'LeadController@callCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/leads/{id}/call', [
    'as' => 'leads.calls.store',
    'uses' => 'LeadController@callStore',
]
)->middleware(['auth']);
Route::get(
    '/leads/{id}/call/{cid}/edit', [
    'as' => 'leads.calls.edit',
    'uses' => 'LeadController@callEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put(
    '/leads/{id}/call/{cid}', [
    'as' => 'leads.calls.update',
    'uses' => 'LeadController@callUpdate',
]
)->middleware(['auth']);
Route::delete(
    '/leads/{id}/call/{cid}', [
    'as' => 'leads.calls.destroy',
    'uses' => 'LeadController@callDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Lead Email
Route::get(
    '/leads/{id}/email', [
    'as' => 'leads.emails.create',
    'uses' => 'LeadController@emailCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/leads/{id}/email', [
    'as' => 'leads.emails.store',
    'uses' => 'LeadController@emailStore',
]
)->middleware(['auth']);
Route::resource('leads', 'LeadController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
// end Leads Module

Route::get('user/{id}/plan', 'UserController@upgradePlan')->name('plan.upgrade')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('user/{id}/plan/{pid}', 'UserController@activePlan')->name('plan.active')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/{uid}/notification/seen', [
    'as' => 'notification.seen',
    'uses' => 'UserController@notificationSeen',
]
);

// Email Templates
Route::get('email_template_lang/{id}/{lang?}', 'EmailTemplateController@manageEmailLang')->name('manage.email.language')->middleware(['auth']);
Route::put('email_template_store/{pid}', 'EmailTemplateController@storeEmailLang')->name('store.email.language')->middleware(['auth']);
Route::put('email_template_status/{id}', 'EmailTemplateController@updateStatus')->name('status.email.language')->middleware(['auth']);
Route::resource('email_template', 'EmailTemplateController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
// End Email Templates

// HRM

Route::resource('user', 'UserController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('employee/json', 'EmployeeController@json')->name('employee.json')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('branch/employee/json', 'EmployeeController@employeeJson')->name('branch.employee.json')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('employee-profile', 'EmployeeController@profile')->name('employee.profile')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('show-employee-profile/{id}', 'EmployeeController@profileShow')->name('show.employee.profile')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('lastlogin', 'EmployeeController@lastLogin')->name('lastlogin')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('employee', 'EmployeeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('department', 'DepartmentController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('designation', 'DesignationController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('document', 'DocumentController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('branch', 'BranchController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


// Hrm EmployeeController


Route::get('employee/salary/{eid}', 'SetSalaryController@employeeBasicSalary')->name('employee.basic.salary')->middleware(
    [
        'auth',
        'XSS',
    ]
);
//payslip

Route::resource('paysliptype', 'PayslipTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('allowance', 'AllowanceController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('commission', 'CommissionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('allowanceoption', 'AllowanceOptionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('loanoption', 'LoanOptionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('deductionoption', 'DeductionOptionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('loan', 'LoanController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('saturationdeduction', 'SaturationDeductionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('otherpayment', 'OtherPaymentController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('overtime', 'OvertimeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('employee/salary/{eid}', 'SetSalaryController@employeeBasicSalary')->name('employee.basic.salary')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('employee/update/sallary/{id}', 'SetSalaryController@employeeUpdateSalary')->name('employee.salary.update')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('salary/employeeSalary', 'SetSalaryController@employeeSalary')->name('employeesalary')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('setsalary', 'SetSalaryController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('allowances/create/{eid}', 'AllowanceController@allowanceCreate')->name('allowances.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('commissions/create/{eid}', 'CommissionController@commissionCreate')->name('commissions.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('loans/create/{eid}', 'LoanController@loanCreate')->name('loans.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('saturationdeductions/create/{eid}', 'SaturationDeductionController@saturationdeductionCreate')->name('saturationdeductions.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('otherpayments/create/{eid}', 'OtherPaymentController@otherpaymentCreate')->name('otherpayments.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('overtimes/create/{eid}', 'OvertimeController@overtimeCreate')->name('overtimes.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::get('payslip/paysalary/{id}/{date}', 'PaySlipController@paysalary')->name('payslip.paysalary')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/bulk_pay_create/{date}', 'PaySlipController@bulk_pay_create')->name('payslip.bulk_pay_create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('payslip/bulkpayment/{date}', 'PaySlipController@bulkpayment')->name('payslip.bulkpayment')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('payslip/search_json', 'PaySlipController@search_json')->name('payslip.search_json')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/employeepayslip', 'PaySlipController@employeepayslip')->name('payslip.employeepayslip')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/showemployee/{id}', 'PaySlipController@showemployee')->name('payslip.showemployee')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/editemployee/{id}', 'PaySlipController@editemployee')->name('payslip.editemployee')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('payslip/editemployee/{id}', 'PaySlipController@updateEmployee')->name('payslip.updateemployee')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/pdf/{id}/{m}', 'PaySlipController@pdf')->name('payslip.pdf')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/payslipPdf/{id}', 'PaySlipController@payslipPdf')->name('payslip.payslipPdf')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/send/{id}/{m}', 'PaySlipController@send')->name('payslip.send')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('payslip/delete/{id}', 'PaySlipController@destroy')->name('payslip.delete')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('payslip', 'PaySlipController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('company-policy', 'CompanyPolicyController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('indicator', 'IndicatorController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::resource('appraisal', 'AppraisalController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('branch/employee/json', 'EmployeeController@employeeJson')->name('branch.employee.json')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('goaltype', 'GoalTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('goaltracking', 'GoalTrackingController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('account-assets', 'AssetController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post('event/getdepartment', 'EventController@getdepartment')->name('event.getdepartment')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('event/getemployee', 'EventController@getemployee')->name('event.getemployee')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('event', 'EventController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('meeting/getdepartment', 'MeetingController@getdepartment')->name('meeting.getdepartment')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('meeting/getemployee', 'MeetingController@getemployee')->name('meeting.getemployee')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('meeting', 'MeetingController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('trainingtype', 'TrainingTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('trainer', 'TrainerController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('training/status', 'TrainingController@updateStatus')->name('training.status')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('training', 'TrainingController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


// HRM - HR Module

Route::resource('awardtype', 'AwardTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('award', 'AwardController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('resignation', 'ResignationController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('travel', 'TravelController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('promotion', 'PromotionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('complaint', 'ComplaintController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('warning', 'WarningController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('termination', 'TerminationController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('terminationtype', 'TerminationTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('announcement/getdepartment', 'AnnouncementController@getdepartment')->name('announcement.getdepartment')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('announcement/getemployee', 'AnnouncementController@getemployee')->name('announcement.getemployee')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('announcement', 'AnnouncementController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('holiday', 'HolidayController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('holiday-calender', 'HolidayController@calender')->name('holiday.calender')->middleware(
    [
        'auth',
        'XSS',
    ]
);

//------------------------------------  Recurtment --------------------------------

Route::resource('job-category', 'JobCategoryController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('job-stage', 'JobStageController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('job-stage/order', 'JobStageController@order')->name('job.stage.order')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('job', 'JobController')->middleware(['auth']);
Route::get('career/{id}/{lang}', 'JobController@career')->name('career');
Route::get('job/requirement/{code}/{lang}', 'JobController@jobRequirement')->name('job.requirement');
Route::get('job/apply/{code}/{lang}', 'JobController@jobApply')->name('job.apply');
Route::post('job/apply/data/{code}', 'JobController@jobApplyData')->name('job.apply.data');


Route::get('candidates-job-applications', 'JobApplicationController@candidate')->name('job.application.candidate')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('job-application', 'JobApplicationController')->middleware(['auth']);

Route::post('job-application/order', 'JobApplicationController@order')->name('job.application.order')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('job-application/{id}/rating', 'JobApplicationController@rating')->name('job.application.rating')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete('job-application/{id}/archive', 'JobApplicationController@archive')->name('job.application.archive')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post('job-application/{id}/skill/store', 'JobApplicationController@addSkill')->name('job.application.skill.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('job-application/{id}/note/store', 'JobApplicationController@addNote')->name('job.application.note.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete('job-application/{id}/note/destroy', 'JobApplicationController@destroyNote')->name('job.application.note.destroy')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('job-application/getByJob', 'JobApplicationController@getByJob')->name('get.job.application')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('job-onboard', 'JobApplicationController@jobOnBoard')->name('job.on.board')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('job-onboard/create/{id}', 'JobApplicationController@jobBoardCreate')->name('job.on.board.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('job-onboard/store/{id}', 'JobApplicationController@jobBoardStore')->name('job.on.board.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('job-onboard/edit/{id}', 'JobApplicationController@jobBoardEdit')->name('job.on.board.edit')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('job-onboard/update/{id}', 'JobApplicationController@jobBoardUpdate')->name('job.on.board.update')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete('job-onboard/delete/{id}', 'JobApplicationController@jobBoardDelete')->name('job.on.board.delete')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('job-onboard/convert/{id}', 'JobApplicationController@jobBoardConvert')->name('job.on.board.convert')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('job-onboard/convert/{id}', 'JobApplicationController@jobBoardConvertData')->name('job.on.board.convert')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post('job-application/stage/change', 'JobApplicationController@stageChange')->name('job.application.stage.change')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('custom-question', 'CustomQuestionController')->middleware(['auth']);
Route::resource('interview-schedule', 'InterviewScheduleController')->middleware(['auth']);
Route::get('interview-schedule/create/{id?}', 'InterviewScheduleController@create')->name('interview-schedule.create')->middleware(['auth']);
Route::get(
    'taskboard/{view?}', [
    'as' => 'taskBoard.view',
    'uses' => 'ProjectTaskController@taskBoard',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'taskboard-view', [
    'as' => 'project.taskboard.view',
    'uses' => 'ProjectTaskController@taskboardView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('document-upload', 'DucumentUploadController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('transfer', 'TransferController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('attendanceemployee/bulkattendance', 'AttendanceEmployeeController@bulkAttendance')->name('attendanceemployee.bulkattendance')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('attendanceemployee/bulkattendance', 'AttendanceEmployeeController@bulkAttendanceData')->name('attendanceemployee.bulkattendance')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post('attendanceemployee/attendance', 'AttendanceEmployeeController@attendance')->name('attendanceemployee.attendance')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('attendanceemployee', 'AttendanceEmployeeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('leavetype', 'LeaveTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('report/leave', 'ReportController@leave')->name('report.leave')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', 'ReportController@employeeLeave')->name('report.employee.leave')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('leave/{id}/action', 'LeaveController@action')->name('leave.action')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('leave/changeaction', 'LeaveController@changeaction')->name('leave.changeaction')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('leave/jsoncount', 'LeaveController@jsoncount')->name('leave.jsoncount')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('leave', 'LeaveController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('reports-leave', 'ReportController@leave')->name('report.leave')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', 'ReportController@employeeLeave')->name('report.employee.leave')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('reports-payroll', 'ReportController@payroll')->name('report.payroll')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('reports-monthly-attendance', 'ReportController@monthlyAttendance')->name('report.monthly.attendance')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('report/attendance/{month}/{branch}/{department}', 'ReportController@exportCsv')->name('report.attendance')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// User Module
Route::get(
    'users/{view?}', [
    'as' => 'users',
    'uses' => 'UserController@index',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'users-view', [
    'as' => 'filter.user.view',
    'uses' => 'UserController@filterUserView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'checkuserexists', [
    'as' => 'user.exists',
    'uses' => 'UserController@checkUserExists',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'profile', [
    'as' => 'profile',
    'uses' => 'UserController@profile',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/profile', [
    'as' => 'update.profile',
    'uses' => 'UserController@updateProfile',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'user/info/{id}', [
    'as' => 'users.info',
    'uses' => 'UserController@userInfo',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'user/{id}/info/{type}', [
    'as' => 'user.info.popup',
    'uses' => 'UserController@getProjectTask',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    'users/{id}', [
    'as' => 'user.destroy',
    'uses' => 'UserController@destroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
// End User Module

// Search
Route::get(
    '/search', [
    'as' => 'search.json',
    'uses' => 'UserController@search',
]
);
// end

// Milestone Module
Route::get(
    'projects/{id}/milestone', [
    'as' => 'project.milestone',
    'uses' => 'ProjectController@milestone',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    'projects/{id}/milestone', [
    'as' => 'project.milestone.store',
    'uses' => 'ProjectController@milestoneStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'projects/milestone/{id}/edit', [
    'as' => 'project.milestone.edit',
    'uses' => 'ProjectController@milestoneEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    'projects/milestone/{id}', [
    'as' => 'project.milestone.update',
    'uses' => 'ProjectController@milestoneUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    'projects/milestone/{id}', [
    'as' => 'project.milestone.destroy',
    'uses' => 'ProjectController@milestoneDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'projects/milestone/{id}/show', [
    'as' => 'project.milestone.show',
    'uses' => 'ProjectController@milestoneShow',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
// End Milestone

// Project Module
Route::get(
    'invite-project-member/{id}', [
    'as' => 'invite.project.member.view',
    'uses' => 'ProjectController@inviteMemberView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    'invite-project-user-member', [
    'as' => 'invite.project.user.member',
    'uses' => 'ProjectController@inviteProjectUserMember',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'project/{view?}', [
    'as' => 'projects.list',
    'uses' => 'ProjectController@index',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'projects-view', [
    'as' => 'filter.project.view',
    'uses' => 'ProjectController@filterProjectView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('projects/{id}/store-stages/{slug}', 'ProjectController@storeProjectTaskStages')->name('project.stages.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::patch(
    'remove-user-from-project/{project_id}/{user_id}', [
    'as' => 'remove.user.from.project',
    'uses' => 'ProjectController@removeUserFromProject',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'projects-users', [
    'as' => 'project.user',
    'uses' => 'ProjectController@loadUser',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'projects/{id}/gantt/{duration?}', [
    'as' => 'projects.gantt',
    'uses' => 'ProjectController@gantt',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    'projects/{id}/gantt', [
    'as' => 'projects.gantt.post',
    'uses' => 'ProjectController@ganttPost',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('projects', 'ProjectController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// User Permission
Route::get(
    'projects/{id}/user/{uid}/permission', [
    'as' => 'projects.user.permission',
    'uses' => 'ProjectController@userPermission',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    'projects/{id}/user/{uid}/permission', [
    'as' => 'projects.user.permission.store',
    'uses' => 'ProjectController@userPermissionStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
// End Project Module
// Task Module
Route::get(
    'stage/{id}/tasks', [
    'as' => 'stage.tasks',
    'uses' => 'ProjectTaskController@getStageTasks',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Project Task Module
Route::get(
    '/projects/{id}/task', [
    'as' => 'projects.tasks.index',
    'uses' => 'ProjectTaskController@index',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/projects/{pid}/task/{sid}', [
    'as' => 'projects.tasks.create',
    'uses' => 'ProjectTaskController@create',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/projects/{pid}/task/{sid}', [
    'as' => 'projects.tasks.store',
    'uses' => 'ProjectTaskController@store',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/projects/{id}/task/{tid}/show', [
    'as' => 'projects.tasks.show',
    'uses' => 'ProjectTaskController@show',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/projects/{id}/task/{tid}/edit', [
    'as' => 'projects.tasks.edit',
    'uses' => 'ProjectTaskController@edit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/projects/{id}/task/update/{tid}', [
    'as' => 'projects.tasks.update',
    'uses' => 'ProjectTaskController@update',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/projects/{id}/task/{tid}', [
    'as' => 'projects.tasks.destroy',
    'uses' => 'ProjectTaskController@destroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::patch(
    '/projects/{id}/task/order', [
    'as' => 'tasks.update.order',
    'uses' => 'ProjectTaskController@taskOrderUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::patch(
    'update-task-priority-color', [
    'as' => 'update.task.priority.color',
    'uses' => 'ProjectTaskController@updateTaskPriorityColor',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post(
    '/projects/{id}/comment/{tid}/file', [
    'as' => 'comment.store.file',
    'uses' => 'ProjectTaskController@commentStoreFile',
]
);
Route::delete(
    '/projects/{id}/comment/{tid}/file/{fid}', [
    'as' => 'comment.destroy.file',
    'uses' => 'ProjectTaskController@commentDestroyFile',
]
);
Route::post(
    '/projects/{id}/comment/{tid}', [
    'as' => 'comment.store',
    'uses' => 'ProjectTaskController@commentStore',
]
);
Route::delete(
    '/projects/{id}/comment/{tid}/{cid}', [
    'as' => 'comment.destroy',
    'uses' => 'ProjectTaskController@commentDestroy',
]
);
Route::post(
    '/projects/{id}/checklist/{tid}', [
    'as' => 'checklist.store',
    'uses' => 'ProjectTaskController@checklistStore',
]
);
Route::post(
    '/projects/{id}/checklist/update/{cid}', [
    'as' => 'checklist.update',
    'uses' => 'ProjectTaskController@checklistUpdate',
]
);
Route::delete(
    '/projects/{id}/checklist/{cid}', [
    'as' => 'checklist.destroy',
    'uses' => 'ProjectTaskController@checklistDestroy',
]
);
Route::post(
    '/projects/{id}/change/{tid}/fav', [
    'as' => 'change.fav',
    'uses' => 'ProjectTaskController@changeFav',
]
);
Route::post(
    '/projects/{id}/change/{tid}/complete', [
    'as' => 'change.complete',
    'uses' => 'ProjectTaskController@changeCom',
]
);
Route::post(
    '/projects/{id}/change/{tid}/progress', [
    'as' => 'change.progress',
    'uses' => 'ProjectTaskController@changeProg',
]
);
Route::get(
    '/projects/task/{id}/get', [
    'as' => 'projects.tasks.get',
    'uses' => 'ProjectTaskController@taskGet',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::get(
    '/calendar/{id}/show', [
    'as' => 'task.calendar.show',
    'uses' => 'ProjectTaskController@calendarShow',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/calendar/{id}/drag', [
    'as' => 'task.calendar.drag',
    'uses' => 'ProjectTaskController@calendarDrag',
]
);
Route::get(
    'calendar/{task}/{pid?}', [
    'as' => 'task.calendar',
    'uses' => 'ProjectTaskController@calendarView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('project-task-stages', 'TaskStageController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/project-task-stages/order', [
    'as' => 'project-task-stages.order',
    'uses' => 'TaskStageController@order',
]
);
Route::post('project-task-new-stage', 'TaskStageController@storingValue')->name('new-task-stage')->middleware(
    [
        'auth',
        'XSS',
    ]
);
// End Task Module

// Project Expense Module
Route::get(
    '/projects/{id}/expense', [
    'as' => 'projects.expenses.index',
    'uses' => 'ExpenseController@index',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/projects/{pid}/expense/create', [
    'as' => 'projects.expenses.create',
    'uses' => 'ExpenseController@create',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/projects/{pid}/expense/store', [
    'as' => 'projects.expenses.store',
    'uses' => 'ExpenseController@store',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/projects/{id}/expense/{eid}/edit', [
    'as' => 'projects.expenses.edit',
    'uses' => 'ExpenseController@edit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/projects/{id}/expense/{eid}', [
    'as' => 'projects.expenses.update',
    'uses' => 'ExpenseController@update',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/projects/{eid}/expense/', [
    'as' => 'projects.expenses.destroy',
    'uses' => 'ExpenseController@destroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/expense-list', [
    'as' => 'expense.list',
    'uses' => 'ExpenseController@expenseList',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Project Timesheet
Route::get('append-timesheet-task-html', 'TimesheetController@appendTimesheetTaskHTML')->name('append.timesheet.task.html')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('timesheet-table-view', 'TimesheetController@filterTimesheetTableView')->name('filter.timesheet.table.view')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('timesheet-view', 'TimesheetController@filterTimesheetView')->name('filter.timesheet.view')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('timesheet-list', 'TimesheetController@timesheetList')->name('timesheet.list')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('timesheet-list-get', 'TimesheetController@timesheetListGet')->name('timesheet.list.get')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get(
    '/project/{id}/timesheet', [
    'as' => 'timesheet.index',
    'uses' => 'TimesheetController@timesheetView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/project/{id}/timesheet/create', [
    'as' => 'timesheet.create',
    'uses' => 'TimesheetController@timesheetCreate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/project/timesheet', [
    'as' => 'timesheet.store',
    'uses' => 'TimesheetController@timesheetStore',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/project/timesheet/{project_id}/edit/{timesheet_id}', [
    'as' => 'timesheet.edit',
    'uses' => 'TimesheetController@timesheetEdit',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/project/timesheet/update/{timesheet_id}', [
    'as' => 'timesheet.update',
    'uses' => 'TimesheetController@timesheetUpdate',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/project/timesheet/{timesheet_id}', [
    'as' => 'timesheet.destroy',
    'uses' => 'TimesheetController@timesheetDestroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('projectstages', 'ProjectstagesController');
    Route::post(
        '/projectstages/order', [
        'as' => 'projectstages.order',
        'uses' => 'ProjectstagesController@order',
    ]
    );
    Route::post('projects/bug/kanban/order', 'ProjectController@bugKanbanOrder')->name('bug.kanban.order');
    Route::get('projects/{id}/bug/kanban', 'ProjectController@bugKanban')->name('task.bug.kanban');
    Route::get('projects/{id}/bug', 'ProjectController@bug')->name('task.bug');
    Route::get('projects/{id}/bug/create', 'ProjectController@bugCreate')->name('task.bug.create');
    Route::post('projects/{id}/bug/store', 'ProjectController@bugStore')->name('task.bug.store');
    Route::get('projects/{id}/bug/{bid}/edit', 'ProjectController@bugEdit')->name('task.bug.edit');
    Route::post('projects/{id}/bug/{bid}/update', 'ProjectController@bugUpdate')->name('task.bug.update');
    Route::delete('projects/{id}/bug/{bid}/destroy', 'ProjectController@bugDestroy')->name('task.bug.destroy');
    Route::get('projects/{id}/bug/{bid}/show', 'ProjectController@bugShow')->name('task.bug.show');
    Route::post('projects/{id}/bug/{bid}/comment', 'ProjectController@bugCommentStore')->name('bug.comment.store');
    Route::post('projects/bug/{bid}/file', 'ProjectController@bugCommentStoreFile')->name('bug.comment.file.store');
    Route::delete('projects/bug/comment/{id}', 'ProjectController@bugCommentDestroy')->name('bug.comment.destroy');
    Route::delete('projects/bug/file/{id}', 'ProjectController@bugCommentDestroyFile')->name('bug.comment.file.destroy');
    Route::resource('bugstatus', 'BugStatusController');
    Route::post(
        '/bugstatus/order', [
        'as' => 'bugstatus.order',
        'uses' => 'BugStatusController@order',
    ]
    );

    Route::get(
        'bugs-report/{view?}', [
        'as' => 'bugs.view',
        'uses' => 'ProjectTaskController@allBugList',
    ]
    )->middleware(
        [
            'auth',
            'XSS',
        ]
    );

}
);
// User_Todo Module
Route::post(
    '/todo/create', [
    'as' => 'todo.store',
    'uses' => 'UserController@todo_store',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post(
    '/todo/{id}/update', [
    'as' => 'todo.update',
    'uses' => 'UserController@todo_update',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete(
    '/todo/{id}', [
    'as' => 'todo.destroy',
    'uses' => 'UserController@todo_destroy',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/change/mode', [
    'as' => 'change.mode',
    'uses' => 'UserController@changeMode',
]
);

Route::get(
    'dashboard-view', [
    'as' => 'dashboard.view',
    'uses' => 'DashboardController@filterView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    'dashboard', [
    'as' => 'client.dashboard.view',
    'uses' => 'DashboardController@clientView',
]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

// saas
Route::resource('users', 'UserController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('plans', 'PlanController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('coupons', 'CouponController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
// Orders

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('/orders', 'StripePaymentController@index')->name('order.index');
    Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');

}
);
Route::get(
    '/apply-coupon', [
                       'as' => 'apply.coupon',
                       'uses' => 'CouponController@applyCoupon',
                   ]
)->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);


//================================= Form Builder ====================================//


// Form Builder
Route::resource('form_builder', 'FormBuilderController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Form link base view
Route::get('/form/{code}', 'FormBuilderController@formView')->name('form.view')->middleware(['XSS']);
Route::post('/form_view_store', 'FormBuilderController@formViewStore')->name('form.view.store')->middleware(['XSS']);

// Form Field
Route::get('/form_builder/{id}/field', 'FormBuilderController@fieldCreate')->name('form.field.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/form_builder/{id}/field', 'FormBuilderController@fieldStore')->name('form.field.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/form_builder/{id}/field/{fid}/show', 'FormBuilderController@fieldShow')->name('form.field.show')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/form_builder/{id}/field/{fid}/edit', 'FormBuilderController@fieldEdit')->name('form.field.edit')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/form_builder/{id}/field/{fid}', 'FormBuilderController@fieldUpdate')->name('form.field.update')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete('/form_builder/{id}/field/{fid}', 'FormBuilderController@fieldDestroy')->name('form.field.destroy')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Form Response
Route::get('/form_response/{id}', 'FormBuilderController@viewResponse')->name('form.response')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/response/{id}', 'FormBuilderController@responseDetail')->name('response.detail')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Form Field Bind
Route::get('/form_field/{id}', 'FormBuilderController@formFieldBind')->name('form.field.bind')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/form_field_store/{id}', 'FormBuilderController@bindStore')->name('form.bind.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);
// end Form Builder


//================================= Custom Landing Page ====================================//

Route::get('/landingpage', 'LandingPageSectionController@index')->name('custom_landing_page.index')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/LandingPage/show/{id}', 'LandingPageSectionController@show');
Route::post('/LandingPage/setConetent', 'LandingPageSectionController@setConetent')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get(
    '/get_landing_page_section/{name}', function ($name){
    $plans = \DB::table('plans')->get();

    return view('custom_landing_page.' . $name, compact('plans'));
}
);
Route::post('/LandingPage/removeSection/{id}', 'LandingPageSectionController@removeSection')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/LandingPage/setOrder', 'LandingPageSectionController@setOrder')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/LandingPage/copySection', 'LandingPageSectionController@copySection')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/customer/invoice/{id}/', 'InvoiceController@invoiceLink')->name('invoice.link.copy');
Route::post('plan-pay-with-paypal', 'PaypalController@planPayWithPaypal')->name('plan.pay.with.paypal')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::get('{id}/plan-get-payment-status', 'PaypalController@planGetPaymentStatus')->name('plan.get.payment.status')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);




//================================= Plan Payment Gateways  ====================================//

Route::post('/plan-pay-with-paystack',['as' => 'plan.pay.with.paystack','uses' =>'PaystackPaymentController@planPayWithPaystack'])->middleware(['auth','XSS']);
Route::get('/plan/paystack/{pay_id}/{plan_id}', ['as' => 'plan.paystack','uses' => 'PaystackPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-flaterwave',['as' => 'plan.pay.with.flaterwave','uses' =>'FlutterwavePaymentController@planPayWithFlutterwave'])->middleware(['auth','XSS']);
Route::get('/plan/flaterwave/{txref}/{plan_id}', ['as' => 'plan.flaterwave','uses' => 'FlutterwavePaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-razorpay',['as' => 'plan.pay.with.razorpay','uses' =>'RazorpayPaymentController@planPayWithRazorpay'])->middleware(['auth','XSS']);
Route::get('/plan/razorpay/{txref}/{plan_id}', ['as' => 'plan.razorpay','uses' => 'RazorpayPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-paytm',['as' => 'plan.pay.with.paytm','uses' =>'PaytmPaymentController@planPayWithPaytm'])->middleware(['auth','XSS']);
Route::post('/plan/paytm/{plan}', ['as' => 'plan.paytm','uses' => 'PaytmPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-mercado',['as' => 'plan.pay.with.mercado','uses' =>'MercadoPaymentController@planPayWithMercado'])->middleware(['auth','XSS']);
Route::get('/plan/mercado/{plan}/{amount}', ['as' => 'plan.mercado','uses' => 'MercadoPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-mollie',['as' => 'plan.pay.with.mollie','uses' =>'MolliePaymentController@planPayWithMollie'])->middleware(['auth','XSS']);
Route::get('/plan/mollie/{plan}', ['as' => 'plan.mollie','uses' => 'MolliePaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-skrill',['as' => 'plan.pay.with.skrill','uses' =>'SkrillPaymentController@planPayWithSkrill'])->middleware(['auth','XSS']);
Route::get('/plan/skrill/{plan}', ['as' => 'plan.skrill','uses' => 'SkrillPaymentController@getPaymentStatus']);

Route::post('/plan-pay-with-coingate',['as' => 'plan.pay.with.coingate','uses' =>'CoingatePaymentController@planPayWithCoingate'])->middleware(['auth','XSS']);
Route::get('/plan/coingate/{plan}', ['as' => 'plan.coingate','uses' => 'CoingatePaymentController@getPaymentStatus']);



Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('order', 'StripePaymentController@index')->name('order.index');
    Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');
}
);


Route::post('plan-pay-with-paypal', 'PaypalController@planPayWithPaypal')->name('plan.pay.with.paypal')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::get('{id}/plan-get-payment-status', 'PaypalController@planGetPaymentStatus')->name('plan.get.payment.status')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);



//================================= Invoice Payment Gateways  ====================================//


Route::post('customer/{id}/payment', 'StripePaymentController@addpayment')->name('customer.payment');

Route::post('{id}/pay-with-paypal', 'PaypalController@customerPayWithPaypal')->name('customer.pay.with.paypal');
Route::get('{id}/get-payment-status', 'PaypalController@customerGetPaymentStatus')->name('customer.get.payment.status')->middleware(
    [
        'XSS',

    ]
);


Route::post('/customer-pay-with-paystack',['as' => 'customer.pay.with.paystack','uses' =>'PaystackPaymentController@customerPayWithPaystack'])->middleware(['XSS']);
Route::get('/customer/paystack/{pay_id}/{invoice_id}', ['as' => 'customer.paystack','uses' => 'PaystackPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-flaterwave',['as' => 'customer.pay.with.flaterwave','uses' =>'FlutterwavePaymentController@customerPayWithFlutterwave'])->middleware(['XSS']);
Route::get('/customer/flaterwave/{txref}/{invoice_id}', ['as' => 'customer.flaterwave','uses' => 'FlutterwavePaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-razorpay',['as' => 'customer.pay.with.razorpay','uses' =>'RazorpayPaymentController@customerPayWithRazorpay'])->middleware(['XSS']);
Route::get('/customer/razorpay/{txref}/{invoice_id}', ['as' => 'customer.razorpay','uses' => 'RazorpayPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-paytm',['as' => 'customer.pay.with.paytm','uses' =>'PaytmPaymentController@customerPayWithPaytm'])->middleware(['XSS']);
Route::post('/customer/paytm/{invoice}/{amount}', ['as' => 'customer.paytm','uses' => 'PaytmPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-mercado',['as' => 'customer.pay.with.mercado','uses' =>'MercadoPaymentController@customerPayWithMercado'])->middleware(['XSS']);
Route::get('/customer/mercado/{invoice}', ['as' => 'customer.mercado','uses' => 'MercadoPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-mollie',['as' => 'customer.pay.with.mollie','uses' =>'MolliePaymentController@customerPayWithMollie'])->middleware(['XSS']);
Route::get('/customer/mollie/{invoice}/{amount}', ['as' => 'customer.mollie','uses' => 'MolliePaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-skrill',['as' => 'customer.pay.with.skrill','uses' =>'SkrillPaymentController@customerPayWithSkrill'])->middleware(['XSS']);
Route::get('/customer/skrill/{invoice}/{amount}', ['as' => 'customer.skrill','uses' => 'SkrillPaymentController@getInvoicePaymentStatus']);

Route::post('/customer-pay-with-coingate',['as' => 'customer.pay.with.coingate','uses' =>'CoingatePaymentController@customerPayWithCoingate'])->middleware(['XSS']);
Route::get('/customer/coingate/{invoice}/{amount}', ['as' => 'customer.coingate','uses' => 'CoingatePaymentController@getInvoicePaymentStatus']);




Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('support/{id}/reply', 'SupportController@reply')->name('support.reply');
    Route::post('support/{id}/reply', 'SupportController@replyAnswer')->name('support.reply.answer');
    Route::get('support/grid', 'SupportController@grid')->name('support.grid');
    Route::resource('support', 'SupportController');
}
);


Route::resource('competencies', 'CompetenciesController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
