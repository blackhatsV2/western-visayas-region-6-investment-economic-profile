<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectContent;

class ProjectContentSeeder extends Seeder
{
    public function run(): void
    {
        ProjectContent::truncate();

        $data = [
            // [Page 0] Metadata (Invisible in PDF body)
            [
                'page_number' => 0,
                'section_title' => 'Global Settings',
                'type' => 'metadata',
                'year_range' => '2024-2025',
                'content' => [
                    'site_title' => 'Western Visayas: Investment and Economic Profile',
                    'browser_tab_title' => 'Western Visayas Region 6 Profile',
                    'logo_text' => 'DTI Region 6'
                ]
            ],
             // [Page 1] Title Page (Hero) - Visible on Website, Excluded from PDF Body
             [
                'page_number' => 1,
                'section_title' => 'Title Page',
                'type' => 'hero',
                'year_range' => '2024-2025',
                // Source is handled in content logic or separate field
                'source' => 'Supra Regional Consultations, RDC VI, NEDA VI, PSA VI', 
                'content' => [
                    'title' => "Why Invest in\nWestern Visayas?",
                    'subtitle' => 'DEPARTMENT OF TRADE AND INDUSTRY REGION 6',
                    'logo' => 'dti-logo.png', // Ensure this asset exists or is handled
                    'highlight_stats' => [
                        ['label' => 'GRDP GROWTH (2024)', 'value' => '4.3%'],
                        ['label' => 'GROWING POPULATION', 'value' => '4.9M']
                    ],
                    'modal_details' => [
                        'Why Invest in Visayas Logistics Cluster?' => [ // Matching blade expectation
                            'title' => 'Why Invest in Visayas Logistics Cluster?',
                            'description' => 'A strategic gateway in the heart of the Philippines.'
                        ]
                    ]
                ]
            ],
            // [Page 2] Regional Overview
            [
                'page_number' => 2,
                'section_title' => 'Regional Overview',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'description' => 'Western Visayas or Region VI is located at the center of the Philippine archipelago and lies between two large bodies of water, the Sibuyan Sea and the Visayan Sea. Note: Last June 13, 2024, President Bongbong Marcos signed the Republic Act No. 12000 to established the Negros Island Region (NIR).',
                    'stats' => [
                        ['label' => 'Land Area', 'value' => '20,794 sq. km.'],
                        ['label' => 'Population (2024)', 'value' => '4,861,911'],
                        ['label' => 'Density (2024)', 'value' => '370 / km2'],
                        ['label' => 'Coastal/Landlocked', 'value' => 'Coastal']
                    ],
                    'modal_details' => [
                        'Composition' => [
                            'Provinces' => 'Aklan, Antique, Capiz, Guimaras, & Iloilo',
                            'Cities' => '3',
                            'Municipalities' => '98',
                            'Barangays' => '3,209',
                            'Congressional Districts' => '10'
                        ],
                        'Map Labels' => 'Sibuyan Sea, Visayan Sea, BORACAY, AKLAN, KALIBO, CAPIZ, ROXAS CITY, ANTIQUE, ILOILO, ILOILO CITY, SAN JOSE DE BUENAVISTA, GUIMARAS.'
                    ]
                ],
                'source' => 'Philippine Statistics Authority, Census of Population 2024'
            ],
             // [Page 3] Partner Firms Marquee - Visible on Website
             [
                'page_number' => 3,
                'section_title' => 'Partner Firms Marquee',
                'type' => 'marquee',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        'CONCENTRIX', 'TELEPERFORMANCE', 'TRANSCOM', 'IQOR', 
                        'REED ELSEVIER', 'TELUS', 'WNS', 'ASURION', 'SUTHERLAND'
                    ]
                ]
            ],
            
            // [Page 4] GRDP
            [
                'page_number' => 4,
                'section_title' => '2024 Gross Regional Domestic Product',
                'type' => 'chart',
                'year_range' => '2024-2025',
                'content' => [
                    'title' => 'GRDP Growth Rates by Region (2023-2024, %)',
                    'categories' => ['CV (VII)', 'Caraga (XIII)', 'CL (III)', 'Davao (XI)', 'EV (VIII)', 'NorMin (X)', 'NIR', 'NCR', 'CALABARZON', 'SOCCSKSARGEN', 'CV (II)', 'Ilocos', 'Bicol', 'CAR', 'MIMAROPA', 'WV (VI)', 'Zamboanga', 'BARMM'],
                    'series' => [
                        ['name' => 'Growth Rate %', 'data' => [7.3, 6.9, 6.5, 6.3, 6.2, 6.0, 5.9, 5.59, 5.56, 5.5, 5.3, 4.94, 4.92, 4.8, 4.4, 4.3, 4.2, 2.7]]
                    ],
                    'modal_text' => 'In 2024, Central Visayas was the fastest growing region (7.3%). Western Visayas grew by 4.3%.'
                ],
                'source' => 'https://psa.gov.ph/system/files/pad/2024%20GRDP%20Publication.pdf'
            ],
            // [Page 5] Industry Share Narrative
            [
                'page_number' => 5,
                'section_title' => 'Industry Share to GDP',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'description' => 'The economy of Western Visayas grew by 4.3 percent in 2024, slower than the 6.8 percent growth in 2023. The Western Visayas economy was valued at PhP 641.76 billion (2.9% of the country\'s GDP) at constant 2018 prices.',
                    'stats' => [ // Reusing stats grid for key numbers
                        ['label' => '2024 Growth', 'value' => '4.3%'],
                        ['label' => 'Economy Value', 'value' => 'PhP 641.76 B'],
                        ['label' => 'Share to National GDP', 'value' => '2.9%']
                    ]
                ],
                'source' => 'PSA GRDP Publication 2024'
            ],
            // [Page 6] Per Capita GDP
            [
                'page_number' => 6,
                'section_title' => 'Per Capita GDP Growth',
                'type' => 'chart',
                'year_range' => '2024-2025',
                'content' => [
                    'title' => 'Per Capita GDP Growth Rate by Region (2023-2024, %)',
                    'categories' => ['PH', 'NCR', 'CAR', 'I', 'II', 'III', 'IVA', 'MIMAROPA', 'V', 'VI (WV)', 'NIR', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'BARMM'],
                    'series' => [
                        ['name' => 'Growth Rate %', 'data' => [4.8, 5.0, 3.63, 4.7, 4.6, 5.6, 4.3, 3.64, 4.0, 3.62, 5.5, 6.2, 5.4, 3.2, 5.1, 5.3, 4.4, 5.8, 1.0]]
                    ]
                ],
                'source' => 'PSA GRDP Publication 2024'
            ],
            // [Page 7] Growth Rates by Industry
            [
                'page_number' => 7,
                'section_title' => 'Growth Rates by Industry (2023-2024)',
                'type' => 'chart',
                'year_range' => '2024-2025',
                'content' => [
                    'title' => 'Industry Growth Rates (%)',
                    'categories' => [
                        'Professional & Business Services', 'Electricity, Steam, Water', 'Human Health & Social Work', 
                        'Accommodation & Food', 'Transportation & Storage', 'Financial & Insurance', 'Other Services',
                        'Wholesale & Retail Trade', 'Information & Communication', 'Real Estate', 'Public Administration',
                        'Construction', 'Mining & Quarrying', 'Education', 'Manufacturing', 'Agriculture, Forestry, Fishing'
                    ],
                    'series' => [
                        ['name' => 'Growth Rate %', 'data' => [13.7, 13.52, 13.49, 10.4, 8.6, 8.0, 7.6, 7.3, 6.8, 5.3, 3.6, 3.53, 3.48, 3.4, 2.6, -7.3]]
                    ],
                    'modal_text' => 'Top growth: Professional services (13.7%). Decline: Agriculture (-7.3%).'
                ],
                'source' => 'PSA GRDP Publication 2024'
            ],
            // [Page 9] 12 Economic Drivers
            [
                'page_number' => 9,
                'section_title' => 'The 12 Economic Drivers',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        ['name' => 'AGRICULTURE', 'details' => 'Agencies: DA, DOST, NIA, PCA, PhilFIDA, PhilMEC, SUCs, LGUs'],
                        ['name' => 'MARINE & FISHERIES', 'details' => 'Agencies: BFAR, DOST, SUCs, LGUs'],
                        ['name' => 'MSMEs & LARGE MANUFACTURING', 'details' => 'Agencies: DTI, DOST, LGUs'],
                        ['name' => 'IT/BPO/BPMS', 'details' => 'Agencies: DTI, DICT and Private Companies'],
                        ['name' => 'WHOLESALE AND RETAIL', 'details' => 'Agencies: DTI, LGUs'],
                        ['name' => 'TOURISM', 'details' => 'Agencies: DOT, DTI, LGUs'],
                        ['name' => 'PROPERTY DEVELOPMENT', 'details' => 'Agencies: DHSUD, LGUs'],
                        ['name' => 'CONSTRUCTION', 'details' => 'Agencies: DPWH, LGUs'],
                        ['name' => 'HOUSING', 'details' => 'Agencies: DepEd, CHED, DOH, DSWD, LGUs, NHA'],
                        ['name' => 'FINANCIAL INSTITUTIONS', 'details' => 'Agencies: BSP'],
                        ['name' => 'PORT OPERATIONS', 'details' => 'Agencies: PPA, CAAP, LGUs, Marina'],
                        ['name' => 'TRANSPORTATION', 'details' => 'Agencies: LTFRB, LGUs']
                    ]
                ],
                'source' => 'NEDA Region VI'
            ],
            // [Page 10] Business Name Registration
            [
                'page_number' => 10,
                'section_title' => 'DTI Business Name Registration',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'description' => 'Business Name Registration in Western Visayas (2022 - September 4, 2025). Total: 245,236.',
                    'stats' => [
                        ['label' => '2022 Total', 'value' => '56,135'],
                        ['label' => '2024 Total', 'value' => '71,289'],
                        ['label' => '2025 (Partial)', 'value' => '52,187'],
                        ['label' => 'Total Transactions', 'value' => '245,236']
                    ],
                    'modal_details' => [
                        'Transaction Method' => [
                            'Online' => '173,060 (70.57%)',
                            'Hybrid' => '45,188 (18.43%)',
                            'Walkin' => '26,988 (11%)'
                        ],
                        'Gender Distribution' => [
                            'Women' => '155,723 (63.5%)',
                            'Men' => '89,513 (36.5%)'
                        ],
                        'Territorial Scope' => [
                            'Barangay' => '162,943',
                            'City/Prov' => '53,490',
                            'Regional' => '19,096'
                        ]
                    ]
                ],
                'source' => 'https://bnrs.dti.gov.ph/resources/bn-statistics'
            ],
            // [Page 11] Establishments by Province
            [
                'page_number' => 11,
                'section_title' => 'Establishments in Operation (2021-2023)',
                'type' => 'chart',
                'year_range' => '2024-2025',
                'content' => [
                    'title' => 'Number of Establishments by Province',
                    'categories' => ['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo (inc City)', 'Negros Occ (inc Bacolod)'],
                    'series' => [
                        ['name' => '2021', 'data' => [6399, 4304, 7958, 1407, 23230, 30417]],
                        ['name' => '2022', 'data' => [6737, 4380, 8220, 1487, 24148, 30776]],
                        ['name' => '2023', 'data' => [8907, 5719, 9533, 1890, 26395, 33200]]
                    ]
                ],
                'source' => 'PSA Region 6 Special Release - Reference No. 2025-SR18'
            ],
            // [Page 12] Establishment Size
            [
                'page_number' => 12,
                'section_title' => 'Establishment Size Distribution (2023)',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        ['name' => 'Large (223)', 'details' => 'Bacolod City (35.9%), Iloilo City (27.4%), Negros Occ (13.9%), Iloilo (8.1%), Aklan (6.3%), Capiz (5.8%), Antique (2.2%), Guimaras (0.4%)'],
                        ['name' => 'Medium (239)', 'details' => 'Bacolod City (27.6%), Negros Occ (22.2%), Iloilo City (20.5%), Aklan (13.8%), Iloilo (9.6%), Capiz (4.2%), Antique (1.7%), Guimaras (0.4%)'],
                        ['name' => 'Small (6,791)', 'details' => 'Bacolod City (24.0%), Iloilo City (18.7%), Negros Occ (17.3%), Iloilo (14.7%), Aklan (12.3%), Capiz (7.6%), Antique (4.0%), Guimaras (1.4%)'],
                        ['name' => 'Micro (78,391)', 'details' => 'Negros Occ (24.2%), Iloilo (21.2%), Bacolod (14.3%), Capiz (11.5%), Aklan (10.2%), Iloilo City (9.4%), Antique (6.9%), Guimaras (2.3%)']
                    ]
                ],
                'source' => 'PSA Region 6 Special Release'
            ],
            // [Page 13] Total Employment
            [
                'page_number' => 13,
                'section_title' => 'Total Employment (2021-2023)',
                'type' => 'chart',
                'year_range' => '2024-2025',
                'content' => [
                    'title' => 'Employment by Province',
                    'categories' => ['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo (inc City)', 'Negros Occ (inc Bacolod)'],
                    'series' => [
                        ['name' => '2021', 'data' => [30841, 20256, 32406, 4828, 146410, 209600]],
                        ['name' => '2022', 'data' => [32996, 19851, 34791, 5201, 150969, 207238]],
                        ['name' => '2023', 'data' => [51452, 25451, 42683, 6951, 165833, 237824]]
                    ]
                ],
                'source' => 'PSA Region 6 Special Release'
            ],
            // [Page 14] NEW: Employment by Size Breakdown
            [
                'page_number' => 14,
                'section_title' => 'Employment Distribution by Size (2023)',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'description' => 'Distribution of total employment across establishment sizes per province.',
                    'items' => [
                        ['name' => 'Large (124,511)', 'details' => 'Bacolod (45.5%), Iloilo City (24.4%), Negros Occ (10.1%), Iloilo (8.7%), Capiz (3.8%), Antique (3.9%), Aklan (3.3%), Guimaras (0.3%)'],
                        ['name' => 'Medium (32,546)', 'details' => 'Bacolod (27.8%), Negros Occ (21.7%), Iloilo City (20.8%), Aklan (14.1%), Iloilo (9.4%), Capiz (4.0%), Antique (1.7%), Guimaras (0.4%)'],
                        ['name' => 'Small (146,564)', 'details' => 'Bacolod (25.0%), Negros Occ (19.0%), Iloilo City (18.7%), Iloilo (12.9%), Aklan (12.4%), Capiz (7.5%), Antique (3.4%), Guimaras (1.2%)'],
                        ['name' => 'Micro (226,573)', 'details' => 'Negros Occ (23.4%), Iloilo (20.1%), Bacolod (15.5%), Capiz (11.3%), Aklan (10.8%), Iloilo City (10.1%), Antique (6.7%), Guimaras (2.1%)']
                    ]
                ],
                'source' => 'PSA Region 6 Special Release'
            ],
            // [Page 16] Higher Education
            [
                'page_number' => 16,
                'section_title' => 'Higher Education Institutions (HEIs)',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'stats' => [
                        ['label' => 'Total HEIs', 'value' => '102'],
                        ['label' => 'Graduates', 'value' => '20,391'],
                        ['label' => 'Public (SUCs/LUCs)', 'value' => '53'],
                        ['label' => 'Private', 'value' => '49']
                    ],
                    'modal_details' => [
                        'Breakdown by Location' => [
                            'Iloilo City' => '29 (Public: 3, Private: 26)',
                            'Iloilo' => '27 (Public: 23, Private: 4)',
                            'Capiz' => '17 (Public: 9, Private: 8)',
                            'Aklan' => '16 (Public: 9, Private: 7)',
                            'Antique' => '9 (Public: 6, Private: 3)',
                            'Guimaras' => '4 (Public: 3, Private: 1)'
                        ]
                    ]
                ],
                'source' => 'CHED - Statistical Bulletin 2024-2025'
            ],
            // [Page 17] NEW: HEI Disciplines
            [
                'page_number' => 17,
                'section_title' => 'HEI Distribution by Discipline',
                'type' => 'chart', // Using chart table format for clear data
                'year_range' => '2024-2025',
                'content' => [
                    'title' => 'Institutional Type by Discipline',
                    'categories' => [
                        'Education Science', 'Business Admin', 'Engineering & Tech', 'IT-Related', 'Agriculture/Forestry', 'Medical & Allied',
                        'Social Sciences', 'Service Trades', 'Natural Science', 'Humanities', 'Maritime', 'Mathematics', 'Mass Comm', 'Religion',
                        'Architecture', 'Fine Arts', 'Law', 'Home Economics', 'Other'
                    ],
                    'series' => [
                        ['name' => 'Public', 'data' => [345, 126, 152, 60, 93, 8, 14, 13, 19, 15, 2, 11, 8, 0, 5, 2, 1, 4, 20]],
                        ['name' => 'Private', 'data' => [156, 160, 41, 42, 2, 58, 20, 19, 12, 13, 20, 0, 3, 9, 2, 5, 5, 0, 20]]
                    ]
                ],
                'source' => 'CHED - Statistical Bulletin'
            ],
            // [Page 19] Transportation
            [
                'page_number' => 19,
                'section_title' => 'Transportation Infrastructure',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        ['name' => '9 Airports', 'details' => '6 CAAP-operated, 3 Private (Sipalay, Sicogon, Semirara).'],
                        ['name' => '152 Ports', 'details' => '49 Fishing, 69 Private Comm., 23 Public Comm., 11 Feeder.']
                    ]
                ],
                'source' => 'CAAP / Wikipedia / WV RSET'
            ],
            // [Page 20] Telecom
            [
                'page_number' => 20,
                'section_title' => 'Telecommunications',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'stats' => [
                        ['label' => 'Cell Towers', 'value' => '1,027'],
                        ['label' => 'Wi-Fi Hotspots', 'value' => '293'],
                        ['label' => 'Fiber-optic', 'value' => '20']
                    ]
                ],
                'source' => 'DICT Region VI'
            ],
            // [Page 22] PEZA
            [
                'page_number' => 22,
                'section_title' => 'Operating PEZA Sites',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'stats' => [
                        ['label' => 'Total', 'value' => '23'],
                        ['label' => 'Bacolod City', 'value' => '12'],
                        ['label' => 'Iloilo City', 'value' => '6'],
                        ['label' => 'Negros Occ', 'value' => '3']
                    ],
                    'modal_details' => ['Others' => 'Aklan: 1, Capiz: 1']
                ],
                'source' => 'PEZA (Feb 2023)'
            ],
            // [Page 23-24] Investment Opportunities
            [
                'page_number' => 23,
                'section_title' => 'Logistics Investment Opportunities',
                'type' => 'list',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        'Seaport, Airport, Railway', 'Warehouse, Cold Storage, Trucking Facility', 'Agri Terminal, Food Terminal, Bagsakan Center',
                        'Processing Plant, Packaging Plant', 'ICT Infrastructure, Economic Zone', 'Roads and Bridges'
                    ]
                ],
                'source' => 'VIZ Logistics Cluster'
            ],
            [
                'page_number' => 24,
                'section_title' => 'Why Invest in Visayas Logistics?',
                'type' => 'list',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        'Abundant Natural Resources & Agricultural Potential', 'Strategic Location & Collaborative Environment',
                        'Competitive Human Capital', 'High Demand for Logistics & Economic Growth Potential',
                        'Presence of Infrastructure & Sufficient Power Supply', 'Generally Peaceful and Orderly'
                    ]
                ],
                'source' => 'VIZ Logistics Cluster'
            ],
            // [Page 25] Priority Industries by Province
            [
                'page_number' => 25,
                'section_title' => 'Priority Industries by Province',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        ['name' => 'ILOILO', 'details' => 'Tourism, Processed Food, IT-BPM'],
                        ['name' => 'GUIMARAS', 'details' => 'Fruits (Mangoes), Nuts (Cashews)'],
                        ['name' => 'ANTIQUE', 'details' => 'Bamboo, Processed Food (Kalamay)'],
                        ['name' => 'AKLAN', 'details' => 'Wearables (Piña), Tourism (Boracay), Processed Food'],
                        ['name' => 'CAPIZ', 'details' => 'Aquamarine (Seafood), IT-BPM'],
                        ['name' => 'NEGROS OCC', 'details' => 'Sugar, Wearables, IT-BPM, Processed Food']
                    ]
                ],
                'source' => 'DTI Western Visayas'
            ],
            // [Page 26] DTI 6 Priority Industries
            [
                'page_number' => 26,
                'section_title' => 'DTI 6 Priority Industries',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        ['name' => 'Coffee', 'details' => '9,914 ha Area Planted, 2,090 MT Production'],
                        ['name' => 'Cacao', 'details' => '1,048 ha Farm Area, 21,988 kg Avg Production'],
                        ['name' => 'Processed Fruits & Nuts', 'details' => 'Mango, Banana, Pineapple, Peanut, Papaya, Calamansi'],
                        ['name' => 'Coconut', 'details' => 'Food (VCO, Vinegar) & Non-Food (Lumber, Copra) Products'],
                        ['name' => 'Bamboo', 'details' => '25,535 ha Planted, 9 SSFs, 5 Anchor Firms'],
                        ['name' => 'Wearables & Homestyle', 'details' => 'Piña, Abaca, Raffia (Aklan, Iloilo)'],
                        ['name' => 'IT-BPM', 'details' => '200+ Companies, 50 Assisted Startups']
                    ]
                ],
                'source' => 'DTI Region VI'
            ],
            // [Page 27] NEW: Bamboo Detailed
            [
                'page_number' => 27,
                'section_title' => 'Bamboo Industry Statistics',
                'type' => 'grid', // Using grid with modal_details for key stats
                'year_range' => '2024-2025',
                'content' => [
                    'description' => 'Major industry sector. 9 SSFs, 5 Anchor Firms, 25,535.85 ha planted (as of Sept 2022).',
                    'items' => [
                        [
                            'name' => 'Yearly Area Planted (Ha.)',
                            'details' => '2013: 74, 2014: 125, 2015: 274.35, 2017: 4063.5, 2018: 1068, 2019: 50, 2020: 12847, 2021: 4714, 2022: 1320'
                        ]
                    ]
                ],
                'source' => 'DTI Region VI - Annual Report'
            ],
            // [Page 28] NEW: Cacao Detailed
            [
                'page_number' => 28,
                'section_title' => 'Cacao Industry Cluster',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'stats' => [
                        ['label' => 'Total Farm Area', 'value' => '1,048.48 ha'],
                        ['label' => 'Area Planted', 'value' => '251 ha'],
                        ['label' => 'Plants (Seedlings)', 'value' => '188,169'],
                        ['label' => 'Bearing Trees', 'value' => '94,158'],
                        ['label' => 'Avg Production/Yr', 'value' => '21,988 kg'],
                        ['label' => 'Farmers/Orgs', 'value' => '230']
                    ]
                ],
                'source' => 'DTI Region VI - CoCa data'
            ],
            // [Page 29] NEW: Coffee Detailed
            [
                'page_number' => 29,
                'section_title' => 'Coffee Industry Cluster',
                'type' => 'stats_grid',
                'year_range' => '2024-2025',
                'content' => [
                    'stats' => [
                        ['label' => 'Area Planted', 'value' => '9,914.32 ha'],
                        ['label' => 'Green Beans Prod', 'value' => '2,089.84 MT'],
                        ['label' => 'Dried Cherries Prod', 'value' => '4,178.68 MT'],
                        ['label' => 'Bearing Trees', 'value' => '5,879,656'],
                        ['label' => 'Avg Yield', 'value' => '0.42 MT/HA'],
                        ['label' => 'Robusta Yield', 'value' => '3,376.26 MT']
                    ],
                    'modal_details' => [
                        'Anchor Firms' => ['Sugar Valley Coffee (Negros Occ)', 'Coffee Culture Roastery (Negros Occ)', 'Kape Iloilo']
                    ]
                ],
                'source' => 'DTI Region VI - CoCa data'
            ],
            // [Page 30] NEW: Coconut Detailed
            [
                'page_number' => 30,
                'section_title' => 'Coconut Farmers & Industry Plan',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        ['name' => 'Food Products', 'details' => '31 Registrants. Includes: Coco Vinegar (5), Cooking Oil (1), VCO (7), Fresh Coconut (5), Palm Oil (2), Whole nut (2).'],
                        ['name' => 'Non-Food Products', 'details' => '387 Registrants. Includes: Coconut Lumber (317), Copra Trader (55), Charcoal (1), Coir (2).'],
                        ['name' => 'Processors', 'details' => '3 Oil Millers']
                    ]
                ],
                'source' => 'PCA Matrix of Registrants (Jan-July 2025)'
            ],
            // [Page 31] NEW: Fruits & Nuts Detailed
            [
                'page_number' => 31,
                'section_title' => 'Processed Fruits & Nuts Statistics',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'description' => 'Priority Commodities: Mango, Pineapple, Papaya, Peanut, Banana, Calamansi, Dragon Fruit, Cashew.',
                    'items' => [
                        ['name' => 'Mango', 'details' => '179,346 MT Production, 11 Processors'],
                        ['name' => 'Banana', 'details' => '757,725 MT Production, 163,209 Ha Area, 90 Processors'],
                        ['name' => 'Pili Nuts', 'details' => '33 MT Production, 271 Ha Area, 1 Processor'],
                        ['name' => 'Peanuts', 'details' => '7,388 MT Production, 9,224 Ha Area, 37 Processors'],
                        ['name' => 'Papaya', 'details' => '15,263 MT Production, 3,402 Ha Area, 7 Processors'],
                        ['name' => 'Calamansi', 'details' => '31 Processors']
                    ]
                ],
                'source' => 'National Processed Fruits and Nuts Roadmap'
            ],
            // [Page 32] Wearables Detailed
            [
                'page_number' => 32,
                'section_title' => 'Wearables & Homestyle Raw Materials',
                'type' => 'grid',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        ['name' => 'Aklan', 'details' => 'Piña, Abaca, Raffia, Nito, Clay, Bariw, Buri Ramie'],
                        ['name' => 'Antique', 'details' => 'Abaca, Buri, Bamboo, Coco Coir, Semi Precious Stones'],
                        ['name' => 'Capiz', 'details' => 'Bamboo, Shells, Abaca, Agsam Vine, Clay'],
                        ['name' => 'Guimaras', 'details' => 'Pandan, Twined Piña, Coco Shells, Nito, Coco Coir'],
                        ['name' => 'Iloilo', 'details' => 'Abaca, Bamboo, Clay, Cotton, Shells'],
                        ['name' => 'Negros Occ', 'details' => 'Bamboo, Clay, Coco Shells, Silk, Pandan, Water Lily']
                    ]
                ],
                'source' => 'DTI Region VI'
            ],
            // [Page 33] IT-BPM
            [
                'page_number' => 33,
                'section_title' => 'IT-BPM Industry Cluster',
                'type' => 'list',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        '200+ leading companies (Concentrix, Teleperformance, Transcom, iQor)',
                        'Goal: Premier location for IT-BPM locators and startups',
                        'Programs: Online Slingshot Region 6, Moonshot TNK',
                        '50 Assisted Startups (2021-2024)'
                    ]
                ],
                'source' => 'DTI Region VI'
            ],
            // [Page 34] Closing Actions
            [
                 'page_number' => 99, // Last page
                 'section_title' => 'Invest Now',
                 'type' => 'cta',
                 'year_range' => '2024-2025',
                 'content' => [
                     'title' => "Ready to Invest\nin Western Visayas?",
                     'description' => 'Contact the Department of Trade and Industry Region 6 for assistance, inquiries, and investment facilitation.',
                     'action_text' => 'Contact DTI Region 6'
                 ]
            ],
            // [Page 35] Strategies
            [
                'page_number' => 35,
                'section_title' => 'Industry Recovery & Growth Strategies',
                'type' => 'list',
                'year_range' => '2024-2025',
                'content' => [
                    'items' => [
                        'Inclusive and Resilient Tourism Development',
                        'Digital Transformation and MSME Empowerment',
                        'Creative and Service Sector Promotion',
                        'Regional Industrialization and Innovation',
                        'Workforce Upskilling and Technology Adoption'
                    ]
                ],
                'source' => 'DTI Western Visayas'
            ]
        ];

        foreach ($data as $item) {
            ProjectContent::create($item);
        }
    }
}
