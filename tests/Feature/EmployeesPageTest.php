<?php

it('serves employees page with correct title', function () {
    $html = $this->get('/employees')->assertOk()->getContent();

    expect($html)
        ->toContain('<title>Employees</title>')
        ->toContain('All Employees')
        ->toContain('id="employeeTable"');
});

it('redirects legacy customer management url to employees', function () {
    $this->get('/customer-management')->assertRedirect('/employees');
});
