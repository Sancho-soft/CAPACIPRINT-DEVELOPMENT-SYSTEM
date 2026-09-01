
**Data Dictionary**

** Table 1. Users**

| Field Name                                   | Data Type    | Description  |
| -------------------------------------------- | ------------ | ------------ |
| id                                           | BIGINT       |              |
| UNSIGNED                                     | Unique user  |              |
| account ID                                   |              |              |
| name                                         | VARCHAR(255) | Full name of |
| the user                                     |              |              |
| email                                        | VARCHAR(255) | Primary      |
| unique email address used for authentication |              |              |
| email_verified_at                            | TIMESTAMP    | Timestamp    |
| when user email was verified                 |              |              |
| password                                     | VARCHAR(255) | Encrypted    |
| password hash                                |              |              |
| role                                         | VARCHAR(255) | User access  |
| role: customer, admin, or staff              |              |              |
| phone                                        | VARCHAR(255) | Contact      |
| telephone or mobile number                   |              |              |
| address                                      | TEXT         | Physical     |
| address or delivery location                 |              |              |
| is_archived                                  | BOOLEAN      | Soft         |
| archival status indicator                    |              |              |
| remember_token                               | VARCHAR(100) | Persistent   |
| authentication session token                 |              |              |
| created_at                                   | TIMESTAMP    | Account      |
| creation timestamp                           |              |              |
| updated_at                                   | TIMESTAMP    | Last profile |
| update timestamp                             |              |              |

**Table 2. Print Requests**

| Field Name                                        | Data Type    | Description  |
| ------------------------------------------------- | ------------ | ------------ |
| id                                                | BIGINT       |              |
| UNSIGNED                                          | Unique print |              |
| request record ID                                 |              |              |
| user_id                                           | BIGINT       |              |
| UNSIGNED                                          | Reference to |              |
| the requesting customer user account              |              |              |
| service                                           | VARCHAR(255) | Print        |
| service category                                  |              |              |
| quantity                                          | INT UNSIGNED | Number of    |
| copies requested                                  |              |              |
| size                                              | VARCHAR(255) | Paper size   |
| or printing dimension                             |              |              |
| material                                          | VARCHAR(255) | Selected     |
| printing material                                 |              |              |
| finishing                                         | VARCHAR(255) | Selected     |
| finishing option                                  |              |              |
| deadline                                          | DATE         | Requested    |
| completion or delivery date                       |              |              |
| preferred_branch                                  | VARCHAR(255) | Customer     |
| preferred branch for processing or pickup         |              |              |
| additional_instructions                           | TEXT         | Custom       |
| instructions or special order notes from customer |              |              |
| design_file_path                                  | VARCHAR(255) | File path of |
| uploaded document or design artwork               |              |              |
| design_file_name                                  | VARCHAR(255) | Original     |
| filename of uploaded design artwork               |              |              |
| design_file_size                                  | VARCHAR(255) | Display file |
| size of uploaded artwork                          |              |              |
| proof_file_path                                   | VARCHAR(255) | Storage path |
| of design proof image or PDF                      |              |              |
| proof_status                                      | ENUM         | Proof review |
| status                                            |              |              |
| proof_notes                                       | TEXT         | Notes or     |
| comments regarding the design proof               |              |              |
| collection_mode                                   | VARCHAR(255) | Fulfillment  |
| method: pickup or shipping                        |              |              |
| status                                            | VARCHAR(255) | Current      |
| print request status                              |              |              |
| created_at                                        | TIMESTAMP    | Print        |
| request submission timestamp                      |              |              |
| updated_at                                        | TIMESTAMP    | Last request |
| update timestamp                                  |              |              |

**Table 3. Quotations**

| Field Name                       | Data Type     | Description |
| -------------------------------- | ------------- | ----------- |
| id                               | BIGINT        |             |
| UNSIGNED                         | Unique        |             |
| quotation record ID              |               |             |
| quotation_number                 | VARCHAR(255)  | Unique      |
| quotation reference string       |               |             |
| print_request_id                 | BIGINT        |             |
| UNSIGNED                         | Reference to  |             |
| the associated print request     |               |             |
| user_id                          | BIGINT        |             |
| UNSIGNED                         | Reference to  |             |
| the customer user account        |               |             |
| base_cost                        | DECIMAL(10,2) | Base        |
| printing service charge          |               |             |
| material_cost                    | DECIMAL(10,2) | Calculated  |
| cost of materials                |               |             |
| finishing_cost                   | DECIMAL(10,2) | Calculated  |
| cost of finishing services       |               |             |
| total_price                      | DECIMAL(10,2) | Final       |
| calculated quotation price       |               |             |
| valid_until                      | DATE          | Expiration  |
| date of the quotation            |               |             |
| status                           | VARCHAR(255)  | Quotation   |
| status                           |               |             |
| notes                            | TEXT          | Additional  |
| pricing notes or staff remarks   |               |             |
| created_at                       | TIMESTAMP     | Quotation   |
| creation timestamp               |               |             |
| updated_at                       | TIMESTAMP     | Last        |
| quotation modification timestamp |               |             |

**Table 4. Orders**

| Field Name                        | Data Type    | Description  |
| --------------------------------- | ------------ | ------------ |
| id                                | BIGINT       |              |
| UNSIGNED                          | Unique order |              |
| record ID                         |              |              |
| order_number                      | VARCHAR(255) | Unique order |
| reference string                  |              |              |
| user_id                           | BIGINT       |              |
| UNSIGNED                          | Reference to |              |
| the customer user account         |              |              |
| print_request_id                  | BIGINT       |              |
| UNSIGNED                          | Reference to |              |
| the linked print request          |              |              |
| quotation_id                      | BIGINT       |              |
| UNSIGNED                          | Reference to |              |
| the associated quotation estimate |              |              |
| assigned_branch                   | VARCHAR(255) | Branch       |
| assigned to fulfill the order     |              |              |
| estimated_completion              | DATE         | Target       |
| estimated completion date         |              |              |
| status                            | VARCHAR(255) | Current      |
| order status                      |              |              |
| payment_status                    | VARCHAR(255) | Payment      |
| status                            |              |              |
| created_at                        | TIMESTAMP    | Order        |
| creation timestamp                |              |              |
| updated_at                        | TIMESTAMP    | Last order   |
| update timestamp                  |              |              |

**Table 5. Payments**

| Field Name                       | Data Type     | Description  |
| -------------------------------- | ------------- | ------------ |
| id                               | BIGINT        |              |
| UNSIGNED                         | Unique        |              |
| payment record ID                |               |              |
| order_id                         | BIGINT        |              |
| UNSIGNED                         | Reference to  |              |
| the associated order             |               |              |
| user_id                          | BIGINT        |              |
| UNSIGNED                         | Reference to  |              |
| the customer user account        |               |              |
| amount                           | DECIMAL(10,2) | Total paid   |
| amount                           |               |              |
| payment_reference                | VARCHAR(255)  | Payment      |
| transaction or reference code    |               |              |
| status                           | VARCHAR(255)  | Payment      |
| status                           |               |              |
| paid_at                          | TIMESTAMP     | Date and     |
| time payment was confirmed       |               |              |
| notes                            | TEXT          | Payment      |
| verification or processing notes |               |              |
| created_at                       | TIMESTAMP     | Payment      |
| record creation timestamp        |               |              |
| updated_at                       | TIMESTAMP     | Last payment |
| update timestamp                 |               |              |

**Table 6. Customer Notifications**

| Field Name                          | Data Type    | Description  |
| ----------------------------------- | ------------ | ------------ |
| id                                  | BIGINT       |              |
| UNSIGNED                            | Unique       |              |
| customer notification ID            |              |              |
| user_id                             | BIGINT       |              |
| UNSIGNED                            | Reference to |              |
| the recipient customer user account |              |              |
| order_id                            | BIGINT       |              |
| UNSIGNED                            | Reference to |              |
| the related order                   |              |              |
| title                               | VARCHAR(255) | Notification |
| title header                        |              |              |
| body                                | TEXT         | Detailed     |
| notification message content        |              |              |
| type                                | VARCHAR(255) | Notification |
| category                            |              |              |
| is_read                             | BOOLEAN      | Read status  |
| flag                                |              |              |
| created_at                          | TIMESTAMP    | Notification |
| dispatch timestamp                  |              |              |
| updated_at                          | TIMESTAMP    | Last         |
| notification update timestamp       |              |              |

**Table 7. Claim References**

| Field Name                                        | Data Type    | Description  |
| ------------------------------------------------- | ------------ | ------------ |
| id                                                | BIGINT       |              |
| UNSIGNED                                          | Unique claim |              |
| reference record ID                               |              |              |
| order_id                                          | BIGINT       |              |
| UNSIGNED                                          | Reference to |              |
| the associated order                              |              |              |
| user_id                                           | BIGINT       |              |
| UNSIGNED                                          | Reference to |              |
| the customer user account                         |              |              |
| claim_code                                        | VARCHAR(255) | Unique       |
| pickup verification code string                   |              |              |
| pickup_branch                                     | VARCHAR(255) | Branch where |
| completed order is available for pickup           |              |              |
| completion_date                                   | DATE         | Date order   |
| became ready for pickup                           |              |              |
| is_claimed                                        | BOOLEAN      | Status       |
| indicator if order has been collected by customer |              |              |
| claimed_at                                        | TIMESTAMP    | Timestamp    |
| when order was collected                          |              |              |
| created_at                                        | TIMESTAMP    | Claim        |
| reference generation timestamp                    |              |              |
| updated_at                                        | TIMESTAMP    | Last status  |
| update timestamp                                  |              |              |

**Table 8. Design Proofs**

| Field Name                                  | Data Type    | Description  |
| ------------------------------------------- | ------------ | ------------ |
| id                                          | BIGINT       |              |
| UNSIGNED                                    | Unique       |              |
| design proof record ID                      |              |              |
| print_request_id                            | BIGINT       |              |
| UNSIGNED                                    | Reference to |              |
| the associated print request                |              |              |
| designer_id                                 | BIGINT       |              |
| UNSIGNED                                    | Reference to |              |
| the assigned designer user account          |              |              |
| version                                     | INT          | Proof        |
| revision iteration version number           |              |              |
| proof_file_path                             | VARCHAR(255) | Storage path |
| of design proof file                        |              |              |
| proof_file_name                             | VARCHAR(255) | Original     |
| filename of uploaded proof file             |              |              |
| proof_file_size                             | VARCHAR(255) | Display file |
| size of proof file                          |              |              |
| production_file_path                        | VARCHAR(255) | Storage path |
| of press-ready production file              |              |              |
| production_file_name                        | VARCHAR(255) | Original     |
| filename of press-ready production file     |              |              |
| designer_notes                              | TEXT         | Technical    |
| notes or comments provided by designer      |              |              |
| customer_feedback                           | TEXT         | Review       |
| feedback or revision requests from customer |              |              |
| status                                      | VARCHAR(255) | Proof status |
| approved_at                                 | TIMESTAMP    | Timestamp    |
| when customer approved proof                |              |              |
| created_at                                  | TIMESTAMP    | Proof upload |
| timestamp                                   |              |              |
| updated_at                                  | TIMESTAMP    | Last proof   |
| update timestamp                            |              |              |

**Table 9. Branches**

| Field Name                            | Data Type    | Description |
| ------------------------------------- | ------------ | ----------- |
| id                                    | BIGINT       |             |
| UNSIGNED                              | Unique       |             |
| branch record ID                      |              |             |
| name                                  | VARCHAR(255) | Official    |
| printing branch name                  |              |             |
| location                              | VARCHAR(255) | Branch city |
| or regional location                  |              |             |
| address                               | VARCHAR(255) | Full        |
| physical address of the branch        |              |             |
| phone                                 | VARCHAR(255) | Branch      |
| contact telephone number              |              |             |
| manager_name                          | VARCHAR(255) | Name of     |
| branch manager in-charge              |              |             |
| status                                | VARCHAR(255) | Operational |
| status: active or inactive            |              |             |
| max_daily_jobs                        | INT          | Maximum     |
| daily job handling capacity threshold |              |             |
| created_at                            | TIMESTAMP    | Branch      |
| registration timestamp                |              |             |
| updated_at                            | TIMESTAMP    | Last branch |
| details update timestamp              |              |             |

**Table 10. Machines**

| Field Name                            | Data Type    | Description  |
| ------------------------------------- | ------------ | ------------ |
| id                                    | BIGINT       |              |
| UNSIGNED                              | Unique       |              |
| machine asset ID                      |              |              |
| branch_id                             | BIGINT       |              |
| UNSIGNED                              | Reference to |              |
| the branch where machine is installed |              |              |
| name                                  | VARCHAR(255) | Machine name |
| or asset identifier label             |              |              |
| type                                  | VARCHAR(255) | Machine      |
| category                              |              |              |
| model                                 | VARCHAR(255) | Machine      |
| model or serial designation           |              |              |
| status                                | VARCHAR(255) | Operational  |
| status                                |              |              |
| jobs_per_day_capacity                 | INT          | Daily job    |
| processing throughput capacity        |              |              |
| notes                                 | TEXT         | Operational  |
| or maintenance specifications notes   |              |              |
| created_at                            | TIMESTAMP    | Asset        |
| registration timestamp                |              |              |
| updated_at                            | TIMESTAMP    | Last asset   |
| update timestamp                      |              |              |

**Table 11. Employees**

| Field Name                       | Data Type    | Description  |
| -------------------------------- | ------------ | ------------ |
| id                               | BIGINT       |              |
| UNSIGNED                         | Unique       |              |
| employee record ID               |              |              |
| user_id                          | BIGINT       |              |
| UNSIGNED                         | Reference to |              |
| linked user account              |              |              |
| branch_id                        | BIGINT       |              |
| UNSIGNED                         | Reference to |              |
| assigned branch                  |              |              |
| name                             | VARCHAR(255) | Full name of |
| the employee                     |              |              |
| position                         | VARCHAR(255) | Job position |
| availability_status              | VARCHAR(255) | Work status: |
| available, on_leave, off_duty    |              |              |
| created_at                       | TIMESTAMP    | Employee     |
| record creation timestamp        |              |              |
| updated_at                       | TIMESTAMP    | Last         |
| employee record update timestamp |              |              |

**Table 12. Machine Logs**

| Field Name                                                  | Data Type    | Description  |
| ----------------------------------------------------------- | ------------ | ------------ |
| id                                                          | BIGINT       |              |
| UNSIGNED                                                    | Unique       |              |
| maintenance log record ID                                   |              |              |
| machine_id                                                  | BIGINT       |              |
| UNSIGNED                                                    | Reference to |              |
| the affected machine asset                                  |              |              |
| reported_by                                                 | BIGINT       |              |
| UNSIGNED                                                    | Reference to |              |
| the user reporting the incident                             |              |              |
| log_type                                                    | ENUM         | Incident log |
| category: breakdown, maintenance, inspection, status_change |              |              |
| issue_description                                           | TEXT         | Detailed     |
| description of machine breakdown or servicing               |              |              |
| status                                                      | ENUM         | Resolution   |
| status: open, in_progress, resolved                         |              |              |
| resolved_at                                                 | TIMESTAMP    | Timestamp    |
| when issue was resolved                                     |              |              |
| created_at                                                  | TIMESTAMP    | Incident     |
| reporting timestamp                                         |              |              |
| updated_at                                                  | TIMESTAMP    | Last         |
| incident log update timestamp                               |              |              |

**Table 13. Materials**

| Field Name                                       | Data Type    | Description    |
| ------------------------------------------------ | ------------ | -------------- |
| id                                               | BIGINT       |                |
| UNSIGNED                                         | Unique       |                |
| material catalog ID                              |              |                |
| name                                             | VARCHAR(255) | Consumable     |
| or printing material name                        |              |                |
| type                                             | VARCHAR(255) | Material       |
| category: paper, ink, lamination, binding, other |              |                |
| unit                                             | VARCHAR(255) | Measurement    |
| unit: sheets, liters, rolls, pcs                 |              |                |
| description                                      | TEXT         | Specifications |
| or description of material                       |              |                |
| is_active                                        | BOOLEAN      | Active         |
| status for quotation calculations                |              |                |
| created_at                                       | TIMESTAMP    | Material       |
| entry creation timestamp                         |              |                |
| updated_at                                       | TIMESTAMP    | Last           |
| material update timestamp                        |              |                |

**Table 14. Branch Inventory**

| Field Name                                 | Data Type     | Description  |
| ------------------------------------------ | ------------- | ------------ |
| id                                         | BIGINT        |              |
| UNSIGNED                                   | Unique        |              |
| branch inventory record ID                 |               |              |
| branch_id                                  | BIGINT        |              |
| UNSIGNED                                   | Reference to  |              |
| the branch                                 |               |              |
| material_id                                | BIGINT        |              |
| UNSIGNED                                   | Reference to  |              |
| the material item                          |               |              |
| quantity                                   | DECIMAL(10,2) | Current      |
| on-hand stock quantity                     |               |              |
| minimum_stock                              | DECIMAL(10,2) | Minimum      |
| reorder threshold quantity                 |               |              |
| status                                     | VARCHAR(255)  | Stock        |
| status: available, low_stock, out_of_stock |               |              |
| last_updated                               | TIMESTAMP     | Timestamp of |
| last stock balance change                  |               |              |
| created_at                                 | TIMESTAMP     | Inventory    |
| entry creation timestamp                   |               |              |
| updated_at                                 | TIMESTAMP     | Last         |
| inventory record update timestamp          |               |              |

**Table 15. Stock Movements**

| Field Name                            | Data Type     | Description |
| ------------------------------------- | ------------- | ----------- |
| id                                    | BIGINT        |             |
| UNSIGNED                              | Unique stock  |             |
| movement record ID                    |               |             |
| branch_id                             | BIGINT        |             |
| UNSIGNED                              | Reference to  |             |
| the branch                            |               |             |
| material_id                           | BIGINT        |             |
| UNSIGNED                              | Reference to  |             |
| the material item                     |               |             |
| user_id                               | BIGINT        |             |
| UNSIGNED                              | Reference to  |             |
| staff member recording movement       |               |             |
| quantity                              | DECIMAL(10,2) | Quantity    |
| added or deducted                     |               |             |
| movement_type                         | VARCHAR(255)  | Movement    |
| type: stock_in, stock_out, adjustment |               |             |
| movement_date                         | DATE          | Effective   |
| date of stock transaction             |               |             |
| reference                             | VARCHAR(255)  | Reference   |
| number                                |               |             |
| reason                                | VARCHAR(255)  | Standard    |
| reason code for stock adjustment      |               |             |
| remarks                               | TEXT          | Additional  |
| remarks or transaction notes          |               |             |
| created_at                            | TIMESTAMP     | Transaction |
| creation timestamp                    |               |             |
| updated_at                            | TIMESTAMP     | Last        |
| transaction update timestamp          |               |             |

**Table 16. Purchase Requests**

| Field Name                                    | Data Type     | Description |
| --------------------------------------------- | ------------- | ----------- |
| id                                            | BIGINT        |             |
| UNSIGNED                                      | Unique        |             |
| purchase request ID                           |               |             |
| branch_id                                     | BIGINT        |             |
| UNSIGNED                                      | Reference to  |             |
| requesting branch                             |               |             |
| requested_by                                  | BIGINT        |             |
| UNSIGNED                                      | Reference to  |             |
| requesting staff user account                 |               |             |
| material_id                                   | BIGINT        |             |
| UNSIGNED                                      | Reference to  |             |
| requested material item                       |               |             |
| quantity                                      | INT           | Quantity of |
| material requested                            |               |             |
| unit_cost                                     | DECIMAL(10,2) | Estimated   |
| cost per unit                                 |               |             |
| total_amount                                  | DECIMAL(12,2) | Total       |
| purchase amount                               |               |             |
| status                                        | ENUM          | Request     |
| status: pending, approved, rejected, received |               |             |
| notes                                         | TEXT          | Procurement |
| justification notes                           |               |             |
| created_at                                    | TIMESTAMP     | Purchase    |
| request submission timestamp                  |               |             |
| updated_at                                    | TIMESTAMP     | Last        |
| purchase request update timestamp             |               |             |

**Table 17. Production Jobs**

| Field Name                                                               | Data Type    | Description  |
| ------------------------------------------------------------------------ | ------------ | ------------ |
| id                                                                       | BIGINT       |              |
| UNSIGNED                                                                 | Unique       |              |
| production job ID                                                        |              |              |
| job_number                                                               | VARCHAR(255) | Unique job   |
| card reference string                                                    |              |              |
| order_id                                                                 | BIGINT       |              |
| UNSIGNED                                                                 | Reference to |              |
| parent order                                                             |              |              |
| branch_id                                                                | BIGINT       |              |
| UNSIGNED                                                                 | Reference to |              |
| assigned processing branch                                               |              |              |
| machine_id                                                               | BIGINT       |              |
| UNSIGNED                                                                 | Reference to |              |
| assigned printing machine                                                |              |              |
| assigned_to                                                              | BIGINT       |              |
| UNSIGNED                                                                 | Reference to |              |
| assigned press operator user account                                     |              |              |
| status                                                                   | VARCHAR(255) | Job status:  |
| assigned, preparing, in_production, quality_checking, completed, delayed |              |              |
| priority                                                                 | VARCHAR(255) | Priority     |
| level: normal, rush, urgent                                              |              |              |
| estimated_hours                                                          | INT          | Estimated    |
| production runtime in hours                                              |              |              |
| delay_reason                                                             | VARCHAR(255) | Explanation  |
| for production delays                                                    |              |              |
| remarks                                                                  | TEXT         | Operator     |
| production notes                                                         |              |              |
| started_at                                                               | TIMESTAMP    | Production   |
| start timestamp                                                          |              |              |
| completed_at                                                             | TIMESTAMP    | Production   |
| completion timestamp                                                     |              |              |
| created_at                                                               | TIMESTAMP    | Job dispatch |
| creation timestamp                                                       |              |              |
| updated_at                                                               | TIMESTAMP    | Last job     |
| update timestamp                                                         |              |              |

**Table 18. Capacity Evaluations**

| Field Name                                    | Data Type    | Description  |
| --------------------------------------------- | ------------ | ------------ |
| id                                            | BIGINT       |              |
| UNSIGNED                                      | Unique       |              |
| evaluation record ID                          |              |              |
| print_request_id                              | BIGINT       |              |
| UNSIGNED                                      | Reference to |              |
| evaluated print request                       |              |              |
| branch_id                                     | BIGINT       |              |
| UNSIGNED                                      | Reference to |              |
| evaluated branch                              |              |              |
| evaluated_by                                  | BIGINT       |              |
| UNSIGNED                                      | Reference to |              |
| evaluator user account                        |              |              |
| machine_score                                 | INT          | Machine      |
| availability rating score (0-100)             |              |              |
| material_score                                | INT          | Raw material |
| availability rating score (0-100)             |              |              |
| employee_score                                | INT          | Staff        |
| availability rating score (0-100)             |              |              |
| workload_score                                | INT          | Active       |
| workload rating score (0-100)                 |              |              |
| deadline_score                                | INT          | Deadline     |
| feasibility rating score (0-100)              |              |              |
| total_score                                   | INT          | Total        |
| weighted capacity evaluation score (0-100)    |              |              |
| capacity_status                               | VARCHAR(255) | Capacity     |
| qualification status                          |              |              |
| available_machines                            | INT          | Count of     |
| compatible operational machines               |              |              |
| current_workload_pct                          | DECIMAL(5,2) | Active       |
| workload utilization percentage               |              |              |
| estimated_completion                          | DATE         | Projected    |
| completion date based on queue                |              |              |
| deadline_feasible                             | BOOLEAN      | Flag         |
| indicating if turnaround deadline is feasible |              |              |
| evaluation_notes                              | TEXT         | Evaluator    |
| notes or algorithm details                    |              |              |
| created_at                                    | TIMESTAMP    | Evaluation   |
| calculation timestamp                         |              |              |
| updated_at                                    | TIMESTAMP    | Last         |
| evaluation update timestamp                   |              |              |

**Table 19. Branch Recommendations**

| Field Name                                                 | Data Type    | Description    |
| ---------------------------------------------------------- | ------------ | -------------- |
| id                                                         | BIGINT       |                |
| UNSIGNED                                                   | Unique       |                |
| branch recommendation ID                                   |              |                |
| print_request_id                                           | BIGINT       |                |
| UNSIGNED                                                   | Reference to |                |
| target print request                                       |              |                |
| order_id                                                   | BIGINT       |                |
| UNSIGNED                                                   | Reference to |                |
| associated order                                           |              |                |
| recommended_branch_id                                      | BIGINT       |                |
| UNSIGNED                                                   | Reference to |                |
| recommended branch                                         |              |                |
| created_by                                                 | BIGINT       |                |
| UNSIGNED                                                   | Reference to |                |
| user account generating recommendation                     |              |                |
| recommendation_score                                       | INT          | System         |
| recommendation confidence score                            |              |                |
| reason                                                     | TEXT         | Rationale      |
| text explaining recommendation                             |              |                |
| status                                                     | VARCHAR(255) | Recommendation |
| status: pending, confirmed, overridden                     |              |                |
| override_reason                                            | TEXT         | Manager        |
| rationale if system recommendation was manually overridden |              |                |
| created_at                                                 | TIMESTAMP    | Recommendation |
| generation timestamp                                       |              |                |
| updated_at                                                 | TIMESTAMP    | Last           |
| recommendation update timestamp                            |              |                |

**Table 20. Pricing Rules**

| Field Name                                       | Data Type     | Description  |
| ------------------------------------------------ | ------------- | ------------ |
| id                                               | BIGINT        |              |
| UNSIGNED                                         | Unique        |              |
| pricing rule ID                                  |               |              |
| service                                          | VARCHAR(255)  | Print        |
| service category matching print_requests.service |               |              |
| size                                             | VARCHAR(255)  | Dimension    |
| specification                                    |               |              |
| base_rate                                        | DECIMAL(10,2) | Base unit    |
| printing rate                                    |               |              |
| material_rate                                    | DECIMAL(10,2) | Material     |
| rate component                                   |               |              |
| finishing_rate                                   | DECIMAL(10,2) | Finishing    |
| service rate component                           |               |              |
| is_active                                        | BOOLEAN       | Pricing rule |
| active status flag                               |               |              |
| created_at                                       | TIMESTAMP     | Rule         |
| creation timestamp                               |               |              |
| updated_at                                       | TIMESTAMP     | Last rule    |
| modification timestamp                           |               |              |

**Table 21. Internal Notifications**

| Field Name                                                                | Data Type    | Description |
| ------------------------------------------------------------------------- | ------------ | ----------- |
| id                                                                        | BIGINT       |             |
| UNSIGNED                                                                  | Unique       |             |
| internal notification ID                                                  |              |             |
| user_id                                                                   | BIGINT       |             |
| UNSIGNED                                                                  | Reference to |             |
| recipient staff user account                                              |              |             |
| order_id                                                                  | BIGINT       |             |
| UNSIGNED                                                                  | Reference to |             |
| associated order                                                          |              |             |
| title                                                                     | VARCHAR(255) | Alert title |
| header                                                                    |              |             |
| body                                                                      | TEXT         | Detailed    |
| internal alert message                                                    |              |             |
| type                                                                      | VARCHAR(255) | Alert type: |
| general, new_request, quotation, payment, production, capacity, inventory |              |             |
| link                                                                      | VARCHAR(255) | Target      |
| portal URL link                                                           |              |             |
| is_read                                                                   | BOOLEAN      | Read status |
| flag                                                                      |              |             |
| created_at                                                                | TIMESTAMP    | Alert       |
| creation timestamp                                                        |              |             |
| updated_at                                                                | TIMESTAMP    | Last alert  |
| update timestamp                                                          |              |             |
