CREATE DATABASE server_mgmt;

USE server_mgmt;

SHOW DATABASES;

FLUSH PRIVILEGES;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

INSERT INTO users (user_id, password, role)
VALUES
('admin1', MD5('admin1'), 'Admin-2025'),
('user1', MD5('userpass'), 'user');

CREATE TABLE hardware (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_perangkat TEXT,
    nama_perangkat VARCHAR(100),
    ip_console VARCHAR(50) DEFAULT NULL,
    tahun_pengadaan INT DEFAULT NULL, 
    serial_number VARCHAR(100) DEFAULT NULL,
    rack VARCHAR(50),
    operating_system VARCHAR(100) DEFAULT NULL,
    no_iventaris VARCHAR(100) DEFAULT NULL,
    fungsi VARCHAR(100) DEFAULT NULL,
    status TEXT DEFAULT NULL,
    keterangan TEXT DEFAULT NULL,
    end_of_support VARCHAR(100) DEFAULT NULL,
    support_vendor VARCHAR(100) DEFAULT NULL,
    kontrak_support_start DATE DEFAULT NULL,
    kontrak_support_finish DATE DEFAULT NULL,
    tanggal_bast DATE DEFAULT NULL,
    lokasi ENUM('DC','DRC') NOT NULL,
    backup_type VARCHAR(50) DEFAULT NULL, 
    ups_status VARCHAR(50) DEFAULT NULL,
	 partner VARCHAR(50),
	 u_position INT DEFAULT NULL,
	 u_height INT DEFAULT 1  
);

INSERT INTO hardware (
	type_perangkat, nama_perangkat, ip_console, tahun_pengadaan, serial_number, rack, operating_system, no_iventaris, fungsi, status,
	keterangan, end_of_support, support_vendor, kontrak_support_start, kontrak_support_finish, tanggal_bast, lokasi, u_position, u_height) 
VALUES 
('Server', 'FUJITSU PRIME QUEST 3800 E2 (HA)', '172.28.203.195:8081', 2023, 'MBAG100093', 'L1-02', 'VM Ware','45AC30D150007','SERVER APLIKASI', NULL,
 'Update', 'Support', 'PT Connet', NULL, NULL, NULL, 'DC', 1, 10),

('Storage', 'CONTROLLER HITACHI VSP E990', '172.28.203.120/121', 2021, '418291', 'L1-02', NULL,'45AC33I210001', 'Storage Database Operasional', NULL,
 'Update', 'Support', 'PT Nashta', '2021-09-23', '2024-09-23', NULL, 'DC', 11, 4),

('Storage', 'STORAGE HITACHI VSP E990', '172.28.203.120/121', 2021, '418291', 'L1-02', NULL,'45AC33I210001', 'Storage Database Operasional', NULL,
 'Update', 'Support', 'PT Nashta', '2021-09-23', '2024-09-23', NULL, 'DC', 15, 2),

('Storage', 'STORAGE HITACHI VSP E990', '172.28.203.120/121', 2021, '418291', 'L1-02', NULL,'45AC33I210001', 'Storage Database Operasional', NULL, 
'Update', 'Support', 'PT Nashta', '2021-09-23', '2024-09-23', NULL, 'DC', 17, 2),

('Storage', 'STORAGE HITACHI VSP E990', '172.28.203.120/121', 2021, '418291', 'L1-02', NULL,'45AC33I210001', 'Storage Database Operasional', NULL, 
'Update', 'Support', 'PT Nashta', '2021-09-23', '2024-09-23', NULL, 'DC', 19, 2),

('Storage', 'HNAS 4060 STORAGE VSP 5100', '172.28.203.11 / 12', 2021, NULL, 'L1-02', NULL, 'Belum BAST', 'STORAGE DATABASE OPERASIONAL', NULL,
 'Update', 'Support', 'PT IBS', NULL, NULL, NULL, 'DC', 21, 3),

('Storage', 'HNAS 4060 STORAGE VSP 5100', '172.28.203.11 / 12', 2021, NULL, 'L1-02', NULL, 'Belum BAST', 'STORAGE DATABASE OPERASIONAL', NULL,
 'Update', 'Support', 'PT IBS', NULL, NULL, NULL, 'DC', 24, 3),

('Storage', 'SYNOLOGY RS 815+', '172.28.203.88:5001', 2017, '1780MRN475400', 'L1-02', NULL, '45AC30J170001', 'Storage NAS Backup (Log WS)', 'Single source PDU',
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 28, 1),

('Storage', 'SYNOLOGY RS 3618 XS', '172.28.201.89:5000', 2017, '1930QNR811XQP', 'L1-02', NULL, '45AC30L170002', 'Storage NAS Backup (Log WS)', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 29, 2),

('Storage', 'SYNOLOGY RX 1217 RP', 'https://172.28.203.89:5001', 2023, NULL, 'L1-02', NULL, NULL, 'Storage NAS Backup (Log WS)', NULL, 
 'Update', 'Support', 'PT SIP', '2022-12-16', '2025-12-16', NULL, 'DC', 31, 2),

('Storage', 'SYNOLOGY RX 1217 RP', 'https://172.28.203.89:5001', 2023, NULL, 'L1-02', NULL, NULL, 'Storage NAS Backup (Log WS)', NULL,
 'Update', 'Support', 'PT SIP', '2022-12-16', '2025-12-16', NULL, 'DC', 33, 2),

('Storage', 'SYNOLOGY RS 3621 XS+', 'https://172.28.203.89:5001', 2023, '22B0VR1TTSE5E', 'L1-02', NULL, NULL, 'Storage NAS Backup (Log WS)', NULL,
 'Update', 'Support', 'PT SIP', '2022-12-16', '2025-12-16', NULL, 'DC', 35, 2),


	#l13
('Server', 'ORACLE M12 2S (QA)', 'https://172.28.203.146', 2022, NULL, 'L1-03', 'Sun OS 11.2', '45AC33A220003', 'Server Database Dev QA (Testing)', NULL, 
 'Update', 'Support', 'PT Metrocom', '2021-11-09', '2024-11-09', NULL, 'DC', 1, 4),

('Server', 'ORACLE M12 2S (PRODUCTION)', 'https://172.28.203.147', 2022, 'PZ52150012', 'L1-03', 'Sun OS 11.2', '45AC33A220004', 'Server Database Production', NULL, 
 'Update', 'Support', 'PT Metrocom', '2021-11-09', '2024-11-09', NULL, 'DC', 5, 4),

('Server', 'ORACLE M12 2S (PRODUCTION)', 'https://172.28.203.147', 2022, 'PZ52150012', 'L1-03', 'Sun OS 11.2', '45AC33A220005', 'Server Database Production', NULL, 
 'Update', 'Support', 'PT Metrocom', '2021-11-09', '2024-11-09', NULL, 'DC', 9, 4),

('Server', 'ORACLE M12 2S (PRODUCTION)', 'https://172.28.203.127/126', 2022, 'PZ52150012', 'L1-03', 'Sun OS 11.2', '45AC33A220006', 'Server Database Production', NULL, 
 'Update', 'Support', 'PT Metrocom', '2021-11-09', '2024-11-09', NULL, 'DC', 13, 4),

('Server', 'ORACLE M12 2S (PRODUCTION)', 'https://172.28.203.127/126', 2022, NULL, 'L1-03', 'Sun OS 11.2', '45AC33A220006', 'Server Database Production', NULL,
 'Update','Support', NULL,  NULL, NULL, NULL, 'DC', 17, 4),

('Storage', 'STORAGE DB NETAPP AFF 800 (HA)', 'https://172.28.203.207/', 2023, NULL, 'L1-03', NULL, 'BELUM DILAPORKAN & BELUM CETAK', 'Storage  DB HA', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 27, 4),

('Switch', 'ETH SWITCH ORACLE M12 -2S HA', '172.28.203.63', 2023, NULL, 'L1-03', NULL, 'BELUM DILAPORKAN & BELUM CETAK', 'Switch DB M12 HA', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 37, 1),
 
	#l14
('Server', 'ORACLE M12 -2S (ODS MDT)', '172.28.203.192', 2022, 'PZ52240004', 'L1-04', NULL, 'BELUM DILAPORKAN & BELUM CETAK', 'SERVER DATABASE ODS OLAP MDT', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL,'DC', 1, 4),
	
('Storage', 'STORAGE NETAPP EF600 ', '172.28.203.193 & 194', 2022, '952201001965', 'L1-04', NULL, 'BELUM DILAPORKAN & BELUM CETAK', 'STORAGE DATABASE ODS OLAP MDT', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 5, 2),
 
('Server', 'ORACLE M12 TESTING NEW', '172.28.203.81', 2025, '952201001965', 'L1-04', NULL, NULL, 'Server DB Testing 2', NULL,
 'Update', 'Support', 'PT MII', NULL, NULL, NULL, 'DC', 15, 4),
 
('Server', 'DELL R 740', '172.28.203.181', 2020, 'BMVBQ03', 'L1-04', NULL, '45AC30F200007', 'Server ITSM (Service Desk Plus / Simfoni)', NULL,
 'Update', 'Support', 'PT Binareka Tata Mandiri', NULL, NULL, NULL, 'DC', 32, 2),

('Server', 'DELL R 440', '172.28.203.185', 2020, '2T14F13', 'L1-04', NULL, '45AC30F200006', 'Server Middleware', NULL,
 'Update', 'Support', 'PT Binareka Tata Mandiri', NULL, NULL, NULL, 'DC', 34, 1),

('Server', 'DELL R 440', '172.28.203.184', 2020, '2SZ9F13', 'L1-04', NULL, '45AC30F200006', 'Server Middleware', NULL,
 'Update', 'Support', 'PT Binareka Tata Mandiri', NULL, NULL, NULL, 'DC', 35, 1),

('Server', 'DELL R 440', '172.28.203.182', 2020, '2T14F13', 'L1-04', NULL, '45AC30F200006', 'Server Report (KVM)', NULL,
 'Update', 'Support', 'PT Binareka Tata Mandiri', NULL, NULL, NULL, 'DC', 37, 1),

('Monotoring Tools', 'RARITAN (SENSOR SUHU & MONITORING)', 'https://172.28.203.15/#/signin', 2022, NULL, 'L1-04', NULL, '45AC33D220001', 'Tools Monitoring Suhu dan Humidity', NULL,
 'Update', 'Support', 'PT Binareka Tata Mandiri', NULL, NULL, NULL, 'DC', 38, 1),


	#L15
('Server', 'LENOVO SR 850', 'https://172.28.203.90/#/login', 2021, 'J302VP20', 'L1-05', NULL, NULL, 'SERVER APLIKASI', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 10, 2),

('Server', 'LENOVO SR 850 (HA)', '172.28.203.44 & ', 2023, 'J9009L0A', 'L1-05', NULL, NULL, 'SERVER APLIKASI HA', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 12, 2),

('Server', 'MacOS Server', NULL, 2025, NULL, 'L1-05', NULL, NULL, 'Server Devsecops', 'Single source PDU',
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 15, 4),

('Server', 'DELL R 660 (SERVER AD)', NULL, 2025, NULL, 'L1-05', NULL, NULL, 'Server Active Directory', NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 26, 1),

('Server', 'DELL EMC POWEREDGE R 740', '172.28.203.180', 2019, NULL, 'L1-05', NULL, '45AC30C190001', 'Server Manage Engine', NULL,
 'Update', 'Support', 'PT Prodata', NULL, NULL, NULL, 'DC', 27, 2),

('Storage', 'SYNOLOGY RS 3618 XS', '172.28.202.88', 2019, '18C0QNR1PWR5F', 'L1-05', NULL, '45AC30C190002', 'Storage NAS Backup', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 29, 2),


	#L16
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.203.224', 2023, NULL, 'L1-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL, 'DC', 2, 2),
	
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.203.225', 2023, NULL, 'L1-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL, 'DC', 4, 2),

('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.203.226', 2023, NULL, 'L1-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL, 'DC', 6, 2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.203.227', 2023, NULL, 'L1-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic SearchServer Elastic Search', NULL,
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL, 'DC', 8,2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.203.228', 2023, NULL, 'L1-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL,'DC', 10, 2),

('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.203.229', 2023, NULL, 'L1-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL,'DC', 12, 2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.203.230', 2023, NULL, 'L1-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL,'DC', 14, 2),
 
('Server', 'DELL POWER EDGE R 750 (REDIS)', '172.28.203.231 ', 2023, NULL, 'L1-06', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL,'DC', 16, 2),
 
('Server', 'DELL POWER EDGE R 750 (REDIS)', '172.28.203.232 ', 2023, NULL, 'L1-06', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL,'DC', 18, 2),
 
('Server', 'DELL POWER EDGE R 750 (REDIS)', '172.28.203.233 ', 2023, NULL, 'L1-06', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Server Elastic Search', NULL, 
 'Update', 'Support', 'PT Sisindokom', '2023-03-13', '2026-03-13', NULL,'DC', 20, 2),
 
('Server', 'LENOVO SR 650 V2', '172.28.203.67 ', 2023, 'J9009VLX ', 'L1-06', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'wsaxis02', NULL, 
 'Update', 'Support', 'PT Binareka', NULL, NULL, NULL,'DC', 22, 2),
 
('Server', 'LENOVO SR 650 V2', '172.28.203.68 ', 2023, 'J9009VLZ ', 'L1-06', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'wsaxis01', NULL, 
 'Update', 'Support', 'PT Binareka', NULL, NULL, NULL,'DC', 24, 2),
 
('Server', 'LENOVO SR 650 V2', '172.28.203.69', 2023, 'J9009VLY ', 'L1-06', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Server Weblogic New', 'amber', 
 'Update', 'Support', 'PT Binareka', NULL, NULL, NULL,'DC', 26, 2),


	#L21
('SAN Switch', 'BROCADE SAN SWITCH G-720', '172.28.203.175', 2022, NULL, 'L2-01', NULL, 'Belum BAST', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 14, 1),

('SAN Switch', 'BROCADE SAN SWITCH G-720', '172.28.203.176', 2022, NULL, 'L2-01', NULL, 'Belum BAST', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 15, 1),

('SAN Switch', 'BROCADE SAN SWITCH G-720 (HA)', '172.28.203.63', 2023, NULL, 'L2-01', NULL, 'Belum BAST', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 16, 1),

(NULL, 'NEW SAN SWITCH DELL ', NULL, NULL, NULL, NULL, NULL, 'Belum BAST', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 17, 1),

('Server', 'HP PROLIANT DL 380 GEN 10', '172.28.203.211', 2018, 'SGH827VFCJ', 'L2-01', NULL, '45AC30A180002', 'Server MariaDB', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 21, 2),

('Server', 'HP PROLIANT DL 380 GEN 10', '172.28.206.5', 2018, 'SGH827VFCL', 'L2-01', NULL, '45AC30A180002', 'Server VM NDO (Spine n Leaf)', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 23, 2),

('Switch', 'SWITCH CEPH', NULL, 2020, NULL, 'L2-01', NULL, '45AC30A200001', 'Koneksi Server Database ke Storage', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 26, 1),

('Storage', 'DELL R 540', '172.28.203.188', 2020, NULL, 'L2-01', NULL, '45AC30A200001', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 27, 2),

('Storage', 'DELL R 540', '172.28.203.187', 2020, NULL, 'L2-01', NULL, '45AC30A200002', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 29, 2),

('Storage', 'DELL R 540', '172.28.203.186', 2020, NULL, 'L2-01', NULL, '45AC30A200003', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 31, 2),

('Security Device', 'DELL R 440 EMC', '?', 2020, NULL, 'L2-01', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Privilage Access Management (PAM)', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 33, 1),

('Security Device', 'DELL R 340 EMC', '172.28.203.38', 2020, NULL, 'L2-01', NULL, '45AC50F200006', 'Privilage Access Management (PAM)', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 34, 1),

('Security Device', 'DELL R 340 EMC', '172.28.203.39', 2020, NULL, 'L2-01', NULL, '45AC50F200006', 'Privilage Access Management (PAM)', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 35, 1),

('Storage', 'IBM FLASH SYSTEM 7200', '172.28.203.86', 2021, NULL, 'L2-01', NULL, '45AC33E210013', 'STORAGE SERVER APLIKASI DC', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC',36, 2),


	#L22
('Switch', 'SWITCH NETSCOUT 5010', NULL, 2024, NULL, 'L2-02', NULL, NULL, 'Switch NDR', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 27, 1),
 
('Switch', 'NETSCOUT NDR', NULL, 2024, NULL, 'L2-02', NULL, NULL, 'Switch NDR', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 28, 3),
 
('Server', 'DELL R 760 (SERVER NDR)', NULL, 2024, NULL, 'L2-02', NULL, NULL, 'Server Network Detection Respone', NULL, 
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 31, 2),
 
('Server', 'DELL R 760 (SERVER NDR)', NULL, 2024, NULL, 'L2-02', NULL, NULL, 'Server Network Detection Respone', NULL,
  NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 33, 2),
  
('Switch', 'SWITCH NETSCOUT 5010', NULL, 2024, NULL, 'L2-02', NULL, NULL, 'Switch NDR', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 35, 1),
 
 
	#L23
('Network Device', 'DMZ CATALYS 9300 SERIES 48 PORT', NULL, 2021, NULL, 'L2-03', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Switch DMZ', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 28, 1),
 
	
	#L24
('CCTV', 'CCTV HIK VISION', '103.82.6.250:8000', 2018, NULL, 'L2-04', NULL,  'BELUM DILAPORKAN', 'Sebagai monitor Keadaan Ruangan DC', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 1, 1),

('Switch', 'CISCO NEXUS 2248 TP-E', NULL, NULL, NULL, 'L2-04', NULL,  NULL, 'Switch NDR', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 5, 1),

('Network Device', 'TRINZIC 1606 (DDI)', NULL, 2024, NULL, 'L2-04', NULL,  NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 13, 1),

('Network Device', 'TRINZIC 1606 (DDI)', NULL, 2024, NULL, 'L2-04', NULL,  NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 14, 1),

('Network Device', 'TRINZIC 1506 (DDI)', NULL, 2024, NULL, 'L2-04', NULL,  NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 15, 1),

('Network Device', 'TRINZIC 1506 (DDI)', NULL, 2024, NULL, 'L2-04', NULL,  NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 16, 1),

('Server', 'HP PROLIANT DL 380 GEN 10 (PRTG)', NULL, 2021, NULL, 'L2-04', NULL,  '45AC33A220001', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 17, 2),

('Switch', 'CATALYS EDGE C8300 (SDWAN 2)', NULL, NULL, NULL, 'L2-04', NULL,  NULL, 'Router SDWAN', NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 22, 1),

('Switch', 'CATALYS EDGE C8300 (SDWAN 1)', NULL, NULL, NULL, 'L2-04', NULL,  NULL, 'Router SDWAN', NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 23, 1),

('Network Device', 'MICROTIK CLOUD CORE 1036 G', NULL, 2016, NULL, 'L2-04', NULL,  '45AC30F160001', 'Sebagai Internet DC', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 24, 1),

('Server', 'HP PROLIANT DL360 GEN 11', NULL, 2024, NULL, 'L2-04', NULL,  NULL, 'Server API Security', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 26, 1),

('Server', 'FUJITSU RX 2520 M1 (SERVER DNS)', '172.28.203.76', 2015, NULL, 'L2-04', NULL,  '45AC30D150007', 'Server Proxy', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 27, 2),

('Network Device', 'JUNIPER MX 204', NULL, 2020, NULL, 'L2-04', NULL,  '45AC52G200002', 'Internet DC', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 30, 1),

('Network Device', 'JUNIPER MX 204', NULL, 2020, NULL, 'L2-04', NULL,  '45AC52G200003', 'Internet DC', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 31, 1),

('Network Device', 'F5 BIG IP I10800', NULL, 2017, NULL, 'L2-04', NULL,  '45AC50K170019', 'Load Balancer', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 33, 1),

('Network Device', 'F5 BIG IP I10800', NULL, 2017, NULL, 'L2-04', NULL,  '45AC50K170019', 'Load Balancer', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 35, 1),
 
 
	#l25
('Security Device', 'DELL R 740 XD', NULL, 2021, NULL, 'L2-05', NULL,'45AC50D210007', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 2, 2),
 
('Security Device', 'DELL R 440', NULL, 2021, NULL, 'L2-05', NULL,'45AC50D210008', NULL, NULL,
'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 5, 1),

('Security Device', 'PALO ALTO 5220', NULL, 2021, NULL, 'L2-05', NULL,'45AC50L210001', 'Firewall DC ', NULL, 
'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 7, 3),

('Security Device', 'PALO ALTO 5220', NULL, 2021, NULL, 'L2-05', NULL,'45AC50L210002', 'Firewall DC ', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 11, 3),
 
('Security Device', 'HP PROLIANT DL 380 GEN 10', NULL, 2018, NULL, 'L2-05', NULL,'45AC30A180002', 'Server MariaDB ?', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 14, 2),
 
('Server', 'SYMANTEC S410', NULL, 2022, NULL, NULL, NULL,NULL, 'Apliance Proxy', NULL,
 NULL, NULL, NULL, NULL, NULL, NULL,'DC', 17, 1),
 
('Security Device', 'PULSE SECURE PSA 3000', NULL, 2019, NULL, 'L2-05', NULL,'45AC50J190019', 'Akses PS dari luar DC', NULL, 
'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 18, 1),

('Security Device', 'PULSE SECURE PSA 5000', NULL, 2018, NULL, 'L2-05', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Akses PS dari luar DC', NULL,
'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 19, 1),

('Security Device', 'DELL R 440 (SIEM)', NULL, 2021, NULL, 'L2-05', NULL,'45AC50D210008', 'Server SIEM', NULL,
'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 20, 1),

('Security Device', 'DELL R 440 (SIEM)', NULL, 2021, NULL, 'L2-05', NULL,'45AC50D210008', 'IPS-IDS', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 21, 1),
 
('Security Device', 'IPS TIPPING POINT 8200-TX', NULL, 2021, NULL, 'L2-05', NULL,'45AC50B210003', 'Server Log Rhythm', NULL,
'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 22, 1),

('Security Device', 'LOGRHYTHM SAAR 5120', NULL, 2022, NULL, 'L2-05', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Server Log Rhythm', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 23, 1),
 
('Security Device', 'LOGRHYTHM DXW 5120', NULL, 2022, NULL, 'L2-05', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Server Log Rhythm', NULL,
'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 24, 2),

('Security Device', 'LOGRHYTHM DX 5500', NULL, 2022, NULL, 'L2-05', NULL,'BELUM DILAPORKAN & BELUM CETAK ', 'Server Log Rhythm', NULL,
'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 26, 2),

('Security Device', 'LOGRHYTHM XM 4500', NULL, 2022, NULL, 'L2-05', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Server Log Rhythm', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 28, 2),
 
('Security Device', 'PALO ALTO 5220', NULL, 2021, NULL, 'L2-05', NULL,NULL, 'Firewall DC Backup', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 32, 3),
 
('Security Device', 'PALO ALTO 5220', NULL, 2021, NULL, 'L2-05', NULL,NULL, 'Firewall DC Master', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 35, 3),
	
 
	#l26
('Network Device', 'CISCO 1900 SERIES', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', NULL,
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 1, 1),

('Network Device', 'CISCO 4300 SERIES', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', 'Milik Danamon',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 6, 1),

('Network Device', 'CISCO 891F', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', 'Milik BRI Syariah',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 9, 1),

(NULL, 'ROUTER 450 G', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 12, 1),

('Network Device', 'BDCOM S2510-C', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', 'Milik Icon +',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 14, 1),

('Network Device', 'SWITCH CISCO 892 FSP', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', NULL,
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 15, 1),

('Network Device', 'MODEM ISCOM 2900 (LINTAS ARTA)', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', 'Milik Lintas Arta',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 16, 1),

('Network Device', 'CATALYST 2950 48 PORT', NULL, 2015, NULL, 'L2-06', NULL, '45AC51D030002', NULL, NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 17, 1),

('Network Device', 'CISCO 800 SERIES', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', 'Milik Bank Muamalat',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 18, 1),

('Network Device', 'CISCO ISR 1100 SERIES', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', 'Milik Bank BRI',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 19, 1),

('Network Device', 'CISCO 1811', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Switch Host To Host', NULL,
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 20, 1),

('Network Device', 'ROUTER MICROTIK CCR 1036 - MAIN', NULL, 2017, NULL, 'L2-06', NULL, '45AC52K170003', 'Switch Internet DC - Main', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 23, 1),

(NULL, 'ROUTER S5750E', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 24, 1),

('Network Device', 'ROUTER MICROTIK CCR 1036 - MAIN', NULL, 2017, NULL, 'L2-06', NULL, '45AC52K170003', 'Switch Internet DC - Standby', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DC', 26, 1),

('Network Device', 'PALO ALTO PA-455 ROUTER NAT H2H', NULL, 2025, NULL, 'L2-06', NULL, NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 29, 1),
 
('Network Device', NULL, NULL, 2025, NULL, 'L2-06', NULL, NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', NULL, NULL),

('Network Device', 'ROUTER CISCO C9300 NM-8X', NULL, 2021, NULL, 'L2-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'SWITCH HOST TO HOST', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 30, 1),

('Network Device', 'ROUTER CISCO C9300 NM-8X', NULL, 2021, NULL, 'L2-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'SWITCH HOST TO HOST', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 31, 1),

('Network Device', 'CISCO 4400', NULL, 2021, NULL, 'L2-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'ROUTER HOST TO HOST', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 33, 3),

('Network Device', 'CISCO 4400', NULL, 2021, NULL, 'L2-06', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'ROUTER HOST TO HOST', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 36, 3),

('Network Device', 'SWITCH FENGINE S 4800', NULL, NULL, NULL, 'L2-06', NULL, 'REKANAN', 'Koneksi to Working Room', 'MilikTelkom Sigma',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 39, 1),

('Network Device', 'SWITCH FENGINE SS800', NULL, 2015, NULL, 'L2-06', NULL, 'REKANAN', 'Koneksi to Working Room', 'MilikTelkom Sigma',
 'Update', 'REKANAN', NULL, NULL, NULL, NULL, 'DC', 40, 1),
 
 
	#l30
('Server', 'ORACLE M12 -2S (HA)', 'https://172.28.203.61', 2023, NULL, 'L3-00', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Switch DB M12 HA', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 1, 4),
 
('Server', 'ORACLE M12 -2S (HA)', NULL, 2023, NULL, 'L3-00', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', 'Switch DB M12 HA', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 5, 4),

('Storage', 'STORAGE DB NETAPP AFF 800 (HA)', 'https://172.28.203.208', 2023, NULL, 'L3-00', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', NULL, NULL,
 'Update', 'Support', 'PT Anomali Lintas Teknologi', '2023-10-30', NULL, NULL, 'DC', 22, 4),

('Storage', 'STORAGE APLIKASI NETAPP AF250 ', 'https://172.28.203.96/sysmgr/v4/storage/tiers', 2023, NULL, 'L3-00', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 26, 2),

('Switch', 'ETH SWITCH ORACLE M12 -2S HA', NULL, 2023, NULL,'L3-00', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 38, 1),

('SAN Switch', 'BROCADE SAN SWITCH G-720 (HA) ', '172.28.203.64', 2023, NULL, 'L3-00', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 39, 1),
	
	
	#l31
('Storage', 'QUANTUM SCALAR I6 (STORAGE TAPE)', '172.28.203.41', 2022, 'FSL2148646', 'L3-01', NULL, 'Belum BAST', 'TAPE BOX', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 1, 12),

('Storage', 'QUANTUM DX i4800 (DELL R 340) (INTERNAL STORAGE)', '172.28.203.40', 2022, 'AV2149BVX00377', 'L3-01', NULL, 'Belum BAST', 'Media Disk ', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 14, 2),

('Storage', 'QUANTUM DX i4800 (DELL R 340) (INTERNAL STORAGE)', '172.28.203.40', 2022, 'AV2149BVX00377', 'L3-01', NULL, 'Belum BAST', 'Server Backup Disk', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 16, 2),

('Server', 'DELL POWER EDGE R 740 (COMVALUT)', '172.28.203.42', 2022, 'DJKZRSK3', 'L3-01', NULL, 'Belum BAST', 'Server Backup Sistem Veem', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 19, 2),

('Server', 'DELL POWER EDGE R 340 (MONITORING BACKUP)', '172.28.203.43', 2022, NULL, 'L3-01', NULL, 'Belum BAST', 'Server Monitoring Veem', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 22, 1),

('Storage', 'STORAGE VSP 5100', '172.28.203.174 -178 (Console)', 2022, '31389', 'L3-01', NULL, 'Belum BAST', 'Storage Database Operasional ', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 24, 4),

('Storage', 'STORAGE VSP 5100', '172.28.203.174 -178 (Console)', 2022, '31389', 'L3-01', NULL, 'Belum BAST', 'Storage Database Operasional ', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 28, 4),

('Switch', 'SWITCH STORAGE VSP 5100', NULL, 2022, NULL, 'L3-01', NULL, 'Belum BAST', 'Switch Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 32, 1),

('Switch', 'SWITCH STORAGE VSP 5100', NULL, 2022, NULL, 'L3-01', NULL, 'Belum BAST', 'Switch Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 33, 1),

('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL, 'L3-01', NULL, 'Belum BAST', 'Storage Database Operasional ', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 34, 2),

('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL , 'L3-01', NULL, 'Belum BAST', 'Storage Database Operasional ', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 36, 2),

('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL, 'L3-01', NULL, 'Belum BAST', 'Storage Database Operasional ', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 38, 2),

('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL, 'L3-01', NULL, 'Belum BAST', 'Storage Database Operasional ', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 40, 2),
 
 
	#l32
('Server', 'HP PROLIANT DL 380 GEN 10', '172.28.203.29', 2022, NULL, 'L3-02', 'RHEL OS 8.5 ', 'Belum BAST', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 2, 2),
 
('Server', 'HP PROLIANT DL 380 GEN 10', '172.28.203.30', 2022, NULL, 'L3-02', 'RHEL OS 8.5 ', 'Belum BAST', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 4, 2),
 
('Server', 'HP PROLIANT DL 380 GEN 10', '172.28.203.31', 2022, NULL, 'L3-02', 'RHEL OS 8.5 ', 'Belum BAST', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 6, 2),
 
('Server', 'HP PROLIANT DL 380 GEN 10', '172.28.203.32', 2022, NULL, 'L3-02', 'RHEL OS 8.5 ', 'Belum BAST', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 8, 2),
 
('Server', 'HP PROLIANT DL 380 GEN 10', '172.28.203.33', 2022, NULL, 'L3-02', 'RHEL OS 8.5 ', 'Belum BAST', 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 10, 2),
 
('Server', 'LENOVO SR 650', '172.28.203.161 (Controller1)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 12, 2),
 
('Server', 'LENOVO SR 650', '172.28.203.162 (Controller2)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 14, 2),
 
('Server', 'LENOVO SR 650', '172.28.203.162 (Controller3)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 16, 2),

('Storage', 'STORAGE GRID NETAPP SGF6112', NULL, 2024, NULL, 'L2-01', NULL, NULL, 'Object Storage Tier 1', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 18, 1),
	 
('Storage', 'STORAGE GRID NETAPP SGF6112', NULL, 2024, NULL, 'L2-01', NULL, NULL, 'Object Storage Tier 1', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 19, 1),
 
('Storage', 'STORAGE GRID NETAPP SGF6112', NULL, 2024, NULL, 'L2-01', NULL, NULL, 'Object Storage Tier 1', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 20, 1),
 
('Storage', 'STORAGE GRID NETAPP SG110', NULL, 2024, NULL, 'L2-01', NULL, NULL, 'Object Storage Tier 1', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 21, 1),
 
('Storage', 'STORAGE GRID NETAPP SG110', NULL, 2024, NULL, 'L2-01', NULL, NULL, 'Object Storage Tier 1', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 22, 1),
 
('Switch', 'SWITCH MGMT ADDITIONAL STORAGE', 'https://172.28.203.209', NULL, NULL, NULL, NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 28, 1),
 
('Storage', 'STORAGE DB NETAPP AFF 800 (HA)', 'https://172.28.203.209', 2023, NULL, 'L3-01', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 29, 4),
 
('Storage', 'STORAGE APLIKASI NETAPP AF250  ', 'https://172.28.203.106/sysmgr/v4/', 2023, NULL, 'L3-01', NULL, 'BELUM DILAPORKAN & BELUM CETAK ', NULL, NULL,
'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 33, 2),

('Switch', 'SWITCH HPE MELANOK CEPH', '172.28.203.27', 2022, NULL, 'L3-02', NULL, 'Belum BAST', 'Switch Ceph', NULL,
'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 37, 1),

('Switch', 'SWITCH HPE MELANOK CEPH', '172.28.203.28', 2022, NULL, 'L3-02', NULL, 'Belum BAST', 'Switch Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL,  NULL, 'DC', 38, 1),
 
 
   #l33
('Server', 'LENOVO FLEX SN550', '"172.28.203.107 172.28.203.108" = Chasis', 2021, 'J303BMDM', 'L3-03', NULL, '45AC33H210001', 'Server Adminduk', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 1, 10),

('Switch', 'CISCO CATALYST 9200L 24PORT', NULL, NULL, NULL, 'L3-03', NULL, 'REKANAN', 'switch sensor suhu dan humidity indosat', NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 34, 1),

('Switch', 'CISCO CATALYST 9200L 24PORT', NULL, NULL, NULL, 'L3-03', NULL, 'REKANAN', 'switch sensor suhu dan humidity indosat', NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DC', 35, 1),
 
	
	#l34
('Server', 'LENOVO SR 650', '172.28.203.158 (Worker1)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 1, 2),

('Server', 'LENOVO SR 650', '172.28.203.159 (Worker2)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 3, 2),

('Server', 'LENOVO SR 650 (WORKER 3)', '172.28.203.160 (Worker3)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 5, 2),

('Server', 'LENOVO SR 650', '172.28.203.164 (Compute1)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Maria DB (Next)', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 7, 2),

('Server', 'LENOVO SR 650', '172.28.203.165 (Computer2)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Maria DB (Next)', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 9, 2),

('Server', 'LENOVO SR 650 (COMPUTE 3)', '172.28.203.166 (Compute3)', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openstack (media backup next)', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 11, 2),

('Server', 'SERVER LENOVO SR 650  (CONTAINIRATION)', 'https://172.28.203.148', 2023, NULL, 'L3-04', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 13, 2),

('Server', 'SERVER LENOVO SR 650  (DATABASE PENDUKUNG)', '172.28.203.149', 2023, NULL, 'L3-04', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 17, 2),

('Server', 'LENOVO SR 650', '172.28.203.167 (Report1) / IT Sec', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 19, 2),

('Server', 'LENOVO SR 650 (REPORT 2)', '172.28.203.168(Report2) / IT Sec', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 21, 2),

('Server', 'LENOVO SR 850', '172.28.203.169 (Dev/QA)', 2022, 'J3049P9A', 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 23, 2),

('Server', 'FUJITSU PRIME QUEST 3800 E2', 'http://172.28.203.202:8081/login.cgi', 2022, 'MBAG100035', 'L3-04', NULL, '45AC33B220001', 'Server Aplikasi Virtualisasi', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 26, 7),

('Switch', 'SWITCH MELANOKS 1', '172.28.203.170', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 35, 1),

('Switch', 'SWITCH MELANOKS 2', '172.28.203.171', 2022, NULL, 'L3-04', NULL, '45AC33B220001', 'Server Openshift & Openstack', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 36, 1),


	#l35
('Switch', 'APIC CONTROLLER 01 (APIC SERVER M3)', NULL, 2023, NULL, 'L3-05', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DC', 1, 1),
 
('Switch', 'APIC CONTROLLER 02 (APIC SERVER M3)', NULL, 2023, NULL, 'L3-05', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', NULL,NULL, NULL, NULL, 'DC', 2, 1),
 
('Switch', 'APIC CONTROLLER 03 (APIC SERVER M3)', NULL, 2023, NULL, 'L3-05', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', NULL,NULL, NULL, NULL, 'DC', 3, 1),
 
('Switch', 'MANAGEMENT SWITCH (C9300 L 48T 4X E)', NULL, 2023, NULL, 'L3-05', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', NULL,NULL, NULL, NULL, 'DC', 17, 1),
 
('Switch', 'CORE 01 (C9500 48Y4C A)', NULL, 2023, NULL, 'L3-05', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP',NULL, NULL, NULL, 'DC', 18, 1),
 
('Switch', 'CORE 02 (C9500 48Y4C A)', NULL, 2023, NULL, 'L3-05', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', NULL,NULL, NULL, NULL, 'DC', 20, 1),


	#l36
('Switch', 'SPINE-201 (NEXUS 9K C9332 C)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 3, 1),
 
('Switch', 'SPINE-202 (NEXUS 9K C9332 C)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 5, 1),
 
('Switch', 'LEAF-101 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 6, 1),
 
('Switch', 'LEAF-102 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 8, 1),
 
('Switch', 'LEAF-103 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 9, 1),
 
('Switch', 'LEAF-104 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 11, 1),
 
('Switch', 'LEAF-105 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 12, 1),
 
('Switch', 'LEAF-106 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 14, 1),
 
('Switch', 'LEAF-121 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL,'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 15,1),
 
('Switch', 'LEAF-122 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 17, 1),
 
('Switch', 'LEAF-123 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 18, 1),
 
('Switch', 'LEAF-103 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L3-06', NULL, NULL, 'SPINE N LEAF', NULL,
 'Update', 'Support', 'PT SIP', NULL, NULL, NULL, 'DC', 20, 1),
 
	
	#DRC
	#l11
('Network Device', 'CATALYS 9300L  48PORT', NULL, 2025, NULL, 'L1-01', NULL, NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 18, 1),
 
('Network Device', 'CATALYS 9300L  48PORT', NULL, 2025, NULL, 'L1-01', NULL, NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 19, 1),

('Network Device', 'PALO ALTO PA-450 ROUTER NAT H2H', NULL, 2025, NULL, 'L1-01', NULL, NULL, NULL, 'Single source PDU',
 NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 20, 1),

('Network Device', 'PALO ALTO PA-455 ROUTER NAT H2H ', NULL, 2025, NULL, 'L1-01', NULL, NULL, NULL, 'Single source PDU',
 NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 21, 1),

('Network Device', 'PALO ALTO PA-455 ROUTER NAT H2H ', NULL, 2025, NULL,'L1-01', NULL, NULL, NULL, NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 22, 1),


	#l12
('Server', 'HA FUJITSU PRIME QUEST 3800 E2', 'http://172.28.103.195:8081/login.cgi', 2023, 'MBAG100094', 'L1-02', NULL, NULL, 'HA Server Aplikasi Virtualisasi', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 1, 7),
 
('Server', 'MacOS Server', NULL, 2025, NULL, 'L1-02', NULL, NULL, 'Server Devsecops', 'Single source PDU',
 NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 9, 4),

('Server', 'LENOVO SYSTEM X 3650 M5', '172.28.103.79', 2017, NULL, 'L1-02', NULL, NULL, 'Server Ceph DRC', 'Single source PDU',
'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 18, 2),

('Server', 'HP PROLIANT DL 380 GEN 10 (pindah di rack spinelef)', '172.28.106.5', 2018, NULL, 'L1-02', NULL, NULL, 'Sever NDO (sudha dipindah rack)', NULL,
'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', NULL, NULL),

('Server', 'DELL R 660 (SERVER AD)', NULL, 2025, NULL,'L1-02', NULL, NULL, 'Server Active Directory', NULL,
 NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 20, 1),
 
 ('Server', 'DELL EMC R730 POWEREDGE', '172.28.103.130', 2017, NULL,'L1-02', NULL, NULL, 'Proxmox (Server ITSM lama, OpenLdap itsec, elastic lama, appman)', NULL,
'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 25, 2), 


	#l13
('Server', 'ORACLE M12 -2S', '172.28.103.146-151', 2022, 'PZ52150011 ', 'L1-03', NULL, NULL, 'SERVER DATABASE DRC', NULL,
'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 1, 4),
 
('Server', 'ORACLE M12 -2S', '172.28.103.146-151', 2022, NULL, 'L1-03', NULL, NULL, 'SERVER DATABASE DRC', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 5, 4),

('Storage', 'STORAGE DB NETAPP AFF 800 (HA)', '172.28.103.207', 2023, NULL, 'L1-03', NULL, NULL, 'Storage HA ', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 22, 4),

('Switch', 'ETH SWITCH ORACLE M12 -2S HA', NULL, 2023, NULL, 'L1-03', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 38, 1),


	#l14
('Server', 'ORACLE M12 -2S (ODS MDT)', '172.28.103.192', 2022, 'PZ52240005  ', 'L1-04', NULL, NULL, 'SERVER DATABASE ODS OLAP MDT', NULL,
'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 1, 4),
 
('Storage', 'STORAGE NETAPP EF600 (STORAGE ODS-MDT)', '172.28.103.193 & 194', 2022, '952201001924', 'L1-04', NULL, NULL, 'STORAGE DATABASE ODS OLAP MDT', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 5, 2),

('Security Device', 'DELL R 340 (SECURITY)', NULL, 2022, NULL, 'L1-04', NULL, NULL, 'Server Security ', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 11, 1),

('Security Device', 'DELL R 340 (SECURITY)', NULL, 2022, NULL, 'L1-04', NULL, NULL, 'Server Security', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 12, 1),
 
('Security Device', 'DELL R 340 (SECURITY)', NULL, 2022, NULL, 'L1-04', NULL, NULL, 'Server Security', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 13, 1),
 
('Server', 'ORACLE T8', '172.28.103.75', 2021, NULL, 'L1-04', NULL, NULL, 'Server Development', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 15, 3),
 
('Server', 'LENOVO SR 850', NULL, 2021, 'J302VP21', 'L1-04', NULL, NULL, 'Server Aplikasi', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 19, 2),
 
('Server', 'HA SERVER APPS LENOVO SR 850', '172.28.103.44', 2023, 'J9009RGE', 'L1-04', NULL, NULL, 'Server HA Aplikasi Virtualisasi', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 21, 2),
 
('Storage', 'NEW SYNO RX 1217 RP', '172.28.103.89:5001', 2023, NULL, 'L1-04', NULL, NULL, 'Storage New Syno', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 23, 2),

('Storage', 'NEW SYNO RS 3621 XS+', NULL, 2023, '22B0VR1Z9MRFF', 'L1-04', NULL, NULL, 'Storage New Syno', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 25, 2),

('Storage', 'NEW SYNO RX 1217 RP', NULL, 2023, NULL, 'L1-04', NULL, NULL, 'Storage New Syno', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 27, 2),
 
('Network Device', 'RARITAN (SENSOR SUHU & MONITORING)', '172.28.103.16', 2021, NULL, 'L1-04', NULL, NULL, 'Tools Monitoring Suhu dan Humidity', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 33, 1),
 
('Server', 'DELL R 540', '172.28.103.187', 2019, NULL, 'L1-04', NULL, NULL, 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 35, 2),
 
('Server', 'DELL R 540', '172.28.103.186', 2019, NULL, 'L1-04', NULL, NULL, 'Storage Ceph', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 37, 2),
 
 
	#l15
('Network Device', 'NEXUS 2000 SERIES 48 PORT', NULL, 2023, NULL, 'L1-05', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 26, 1),
 
( NULL, 'EXTENDER SWITCH 2232 TM', NULL, 2023, NULL, 'L1-05', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 28, 1),
 
( NULL, 'EXTENDER SWITCH 2232 TM', NULL, 2023, NULL, 'L1-05', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 30, 1),

	#l16
('Server', 'FUJITSU RX 2520 M1 (SERVER DNS)', '172.28.103.104/105', 2015, 'YLSK003770', 'L1-06', NULL, NULL, 'Server ES DRC', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 4, 2),
 
('Server', 'FUJITSU RX 2520 M1 (SERVER DNS)', '172.28.103.76', 2015, NULL, 'L1-06', NULL, NULL, 'Server Proxy dan Server DNS', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 6, 2),
 
('Network Device', 'EXTENDER SWITCH 2232 TM (FO)', NULL, NULL, NULL, 'L1-06', NULL, NULL, 'Extender Core Switch (FO)', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 9, 1),
 
('Network Device', 'SYMANTEC S410 (NEW PROXY)', NULL, 2022, NULL, 'L1-06', NULL, NULL, 'New Proxy', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 11, 1),
 
('Security Device', 'CISCO ISR 4451', NULL, 2015, NULL, 'L1-06', NULL, NULL, 'Router WAN DRC (VPN) Master', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 12, 2),
 
('Security Device', 'CISCO ISR 4451', NULL, 2015, NULL, 'L1-06', NULL, NULL, 'Router WAN DRC (VPN) Backup', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 14, 2),
 
( NULL, 'CISCO C8300 (SDWAN)', NULL, NULL, NULL, 'L1-06', NULL, NULL, NULL, 'REKANAN',
 'Update', NULL, NULL, NULL, NULL, NULL, 'DRC', 19, 1),
 
( NULL, 'SWITCH S5750 E', NULL, NULL, NULL, 'L1-06', NULL, NULL, NULL, 'REKANAN',
 'Update', NULL, NULL, NULL, NULL, NULL, 'DRC', 20, 1),
 
( NULL, 'ROUTER  H3C S3100V3 (ICON +)', NULL, NULL, NULL, 'L1-06', NULL, NULL, NULL, 'REKANAN',
 'Update', NULL, NULL, NULL, NULL, NULL, 'DRC', 21, 1),
 
( NULL, 'CISCO VEDGE 2000 (SDWAN)', NULL, NULL, NULL, 'L1-06', NULL, NULL, NULL, 'SEWA',
 'Update', NULL, NULL, NULL, NULL, NULL, 'DRC', 23, 1),
 
( NULL, 'ZTE ZXA10', NULL, NULL, NULL, 'L1-06', NULL, NULL, NULL, 'REKANAN',
 'Update', NULL, NULL, NULL, NULL, NULL, 'DRC', 25, 1),
 
('Network Device', 'JUNIPER MX 204', NULL, 2020, NULL, 'L1-06', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 27, 1),
 
('Network Device', 'JUNIPER MX 204', NULL, 2020, NULL, 'L1-06', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 28, 1),
 
('Network Device', 'SWITCH UNMANAGED (100mbps)', NULL, 2020, NULL, 'L1-06', NULL, NULL, NULL, NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 29, 1),
 
('Network Device', 'SWITCH CEPH', NULL, 2019, NULL, 'L1-06', NULL, NULL, NULL, NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 30, 1),
 
('Network Device', 'F5 BIG IP I7000 SERIES', NULL, 2020, NULL, 'L1-06', NULL, NULL, 'Load Balancer', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 32, 1),
 
('Network Device', 'F5 BIG IP I7000 SERIES', NULL, 2020, NULL, 'L1-06', NULL, NULL, 'Load Balancer', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 34, 1),

	#L17
('Security Device', 'F5 BIG IP 10000 S', NULL, 2015, NULL, 'L1-07', NULL, NULL, 'Load Balancer', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 2, 2),
	
('Security Device', 'PALO ALTO 5220', NULL, 2019, NULL, 'L1-07', NULL, NULL, 'Firewall', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 14, 2),
 
('Security Device', 'PALO ALTO 5220', NULL, 2019, NULL, 'L1-07', NULL, NULL, 'Firewall', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 17, 2),
	
('Security Device', 'F5 BIG IP 10000 S', NULL, 2015, NULL, 'L1-07', NULL, NULL, 'Load Balancer', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 20, 2),
	
('Security Device', 'LogRhythm DXW5120 ', NULL, 2022, NULL, 'L1-07', NULL, NULL, 'Server Log Rhythm', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 22, 2),
	
('Security Device', 'PULSE SECURE PSA 5000', NULL, 2018, NULL, 'L1-07', NULL, NULL, 'Akses PS dari luar DRC', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 24, 1),
	
('Security Device', 'IPS Tipping Point 8200-TX', NULL, 2020, NULL, 'L1-07', NULL, NULL, NULL, NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 25, 1),
	
('Security Device', 'PALO ALTO 5220', NULL, 2019, NULL, 'L1-07', NULL, NULL, 'Firewall', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 29, 2),
	
('Security Device', 'PALO ALTO 5220', NULL, 2019, NULL, 'L1-07', NULL, NULL, 'Firewall', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 31, 2),
	
('Security Device', 'LOGRHYTHM DLR XM4500', NULL, 2022, NULL, 'L1-07', NULL, NULL, 'Server Log Rhythm', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 33, 1),
	
('Security Device', 'LOGRHYTHM DX 5500', NULL, 2022, NULL, 'L1-07', NULL, NULL, 'Server Log Rhythm', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 34, 2),
	
('Security Device', 'LOGRHYTHM DXW 5120', NULL, 2022, NULL, 'L1-07', NULL, NULL, 'Server Log Rhythm', NULL,
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 36, 2),
 
 
	#L18	
('Network Device', 'CISCO CATALYS 9300 48PORT', NULL, 2015, NULL, 'L1-08', NULL, NULL, 'Perangkat DMZ', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 13, 1),
	
('CCTV', 'HIKVISION CCTV', '103.73.235.250:8000', 2015, NULL, 'L1-08', NULL, NULL, NULL,'Single source PDU',
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 14, 1),
	
( NULL, 'SWITCH NETSCOUT 5010', NULL, NULL, NULL, 'L1-08', NULL, NULL, NULL, NULL,
  NULL,  NULL, NULL, NULL, NULL, NULL, 'DRC', 29, 1),
	
( NULL, 'NETSCOUT (NDR)', NULL, NULL, NULL, 'L1-08', NULL, NULL, NULL, NULL,
 NULL,  NULL, NULL, NULL, NULL, NULL, 'DRC', 30, 3),
	
( NULL, 'DELL R 760 (SERVER NDR)', NULL, NULL, NULL, 'L1-08', NULL, NULL, NULL, NULL,
 NULL,  NULL, NULL, NULL, NULL, NULL, 'DRC', 33, 2),
	
( NULL, 'DELL R 760 (SERVER NDR)', NULL, NULL, NULL, 'L1-08', NULL, NULL, NULL, NULL,
 NULL,  NULL, NULL, NULL, NULL, NULL, 'DRC', 35, 2),
	
( NULL, 'SWITCH NETSCOUT 5010', NULL, 2015, NULL, 'L1-08', NULL, NULL, NULL, NULL,
 NULL,  NULL, NULL, NULL, NULL, NULL, 'DRC', 37, 1),
 
('Network Device', 'ROUTER MICROTIK CCR 1036 - MAIN', NULL, NULL, NULL, 'L1-08', NULL, NULL, NULL, NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 38, 1),


	#L19
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.103.224', 2023, NULL, 'L1-09', NULL, NULL, 'Server Elastic Search', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 2, 2),

('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.103.225', 2023, NULL, 'L1-09', NULL, NULL, 'Server Elastic Search', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 4, 2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.103.226', 2023, NULL, 'L1-09', NULL, NULL, 'Server Elastic Search', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 6, 2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.103.227', 2023, NULL, 'L1-09', NULL, NULL, 'Server Elastic Search', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 8, 2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.103.228', 2023, NULL, 'L1-09', NULL, NULL, 'Server Elastic Search', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 10, 2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.103.229', 2023, NULL, 'L1-09', NULL, NULL, 'Server Elastic Search', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 12, 2),
 
('Server', 'DELL POWER EDGE R 750 (ELASTIC SEARCH)', '172.28.103.230', 2023, NULL, 'L1-09', NULL, NULL, 'Server Elastic Search', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 14, 2),
 
('Server', 'DELL POWER EDGE R 750 (REDIS / LOGGING CACHE)', '172.28.103.231', 2023, NULL, 'L1-09', NULL, NULL, 'Server Cache dan Logging', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 16, 2),
 
('Server', 'DELL POWER EDGE R 750 (REDIS / LOGGING CACHE)', '172.28.103.232', 2023, NULL, 'L1-09', NULL, NULL, 'Server Cache dan Logging', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 18, 2),
 
('Server', 'DELL POWER EDGE R 750 (REDIS / LOGGING CACHE)', '172.28.103.233', 2023, NULL, 'L1-09', NULL, NULL, 'Server Cache dan Logging', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 20, 2),
	
('Server', 'LENOVO SR 650 V2 (SERVER WS)', '172.28.103.69', 2023, NULL, 'L1-09', NULL, NULL, 'Server WS New', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 22, 2),
 
('Server', 'LENOVO SR 650 V2 (SERVER WS)', '172.28.103.68', 2023, NULL, 'L1-09', NULL, NULL, 'Server WS New', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 24, 2),
 
('Server', 'LENOVO SR 650 V2 (SERVER WS)', '172.28.103.67', 2023, NULL, 'L1-09', NULL, NULL, 'Server WS New', NULL,
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 26, 2),
 
	#l20
('Server', 'ORACLE M12 -2S HA', '172.28.103.61', 2023, NULL, 'L2-00', NULL, NULL, 'HA Server DB', NULL, 
 'Update', 'Support', 'PT Metrocom', NULL, NULL, NULL, 'DRC', 2, 4),
 
('Server', 'ORACLE M12 -2S HA', '172.28.103.62', 2023, NULL, 'L2-00', NULL, NULL, 'HA Server DB', NULL, 
 'Update', 'Support', 'PT Metrocom', NULL, NULL, NULL, 'DRC', 6, 4),
 
('Server', 'ORACLE M12 -2S STAGING ( DB Report existing)', '172.28.103.65', 2023, NULL, 'L2-00', NULL, NULL, 'HA Server DB', NULL, 
 'Update', 'Support', 'PT Metrocom', NULL, NULL, NULL, 'DRC', 10, 4),
 
('Storage', 'STORAGE DB NETAPP AFF 800 (HA)', '172.28.103.208', 2023, NULL, 'L2-00', NULL, NULL, 'HA Storage DB', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 22, 4),
 
('Storage', 'STORAGE APLIKASI NETAPP AF250', 'https://172.28.103.161/sysmgr/v4/storage/tiers', 2023, '792338000259\n792338000220', 'L2-00', NULL, NULL, 'HA Storage DB', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 26, 2),
 
('Switch', 'ETH SWITCH ORACLE M12 -2S HA', NULL, 2023, NULL, 'L2-00', NULL, NULL, 'Switch Server HA DB', NULL, 
 'Update', 'Support', 'PT Metrocom', NULL, NULL, NULL, 'DRC', 38, 1),
 
('SAN Switch', 'HA SAN SWITCH BROCADE G-720', '172.28.103.70', 2023, NULL, 'L2-00', NULL, NULL, 'HA SAN Switch', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 39, 1),
 
	#l21
('Storage', 'STORAGE NETAPP AFF 400', 'https://172.28.103.131', 2021, NULL, 'L2-01', NULL, NULL, 'Storage Database', NULL, 
 'Update', 'Support', 'PT Metrocom', '2021-05-04', NULL, NULL, 'DRC', NULL, NULL),
 
('Storage', 'CONTOLLER STORAGE NETAPP AF400', NULL, 2021, NULL, 'L2-01', NULL, NULL, 'Storage Database', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', NULL, NULL),
 
('Storage', 'STORAGE GRID NETAPP SGF6112', NULL, 2024, NULL, 'L2-01', NULL, NULL, 'Object Storage Tier 1', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 24, 1),
 
('Storage', 'STORAGE GRID NETAPP SGF6112', NULL, 2024, NULL, 'L2-01', NULL, NULL, NULL, NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 23, 1),
 
('Storage', 'STORAGE GRID NETAPP SGF6112', NULL, 2024, NULL, 'L2-01', NULL, NULL, NULL, NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 22, 1),
 
('Storage', 'STORAGE GRID NETAPP SG110', NULL, 2024, NULL, 'L2-01', NULL, NULL, NULL, NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 21, 1),
 
('Storage', 'STORAGE GRID NETAPP SG110', NULL, 2024, NULL, 'L2-01', NULL, NULL, NULL, NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 20, 1),
 
('Storage', 'STORAGE SERVER APLIKASI DRC NETAPP AF 250', 'https://172.28.103.141/', 2021, NULL, 'L2-01', NULL, NULL, 'STORAGE SERVER APLIKASI DRC', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 26, 2),
 
('Storage', 'SYNOLOGY RS 815+', 'http://172.28.101.88:5001', 2017, '1780MRN348600', 'L2-01', NULL, NULL, 'NAS Bacup (nfs 200.30)', 'Single source PDU', 
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 31, 1),
 
('Storage', 'SYNOLOGY RS 3618 XS', '172.28.101.89:5001', 2017, '1930QNR', 'L2-01', NULL, NULL, 'Storage Nas Operasional & Backup (nfs butik)', NULL, 
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 34, 2),
 
('Storage', 'SYNOLOGY RS 3618 XS', '172.28.101.87:5001', 2017, '1930QNR', 'L2-01', NULL, NULL, 'Storage Nas Operasional & Backup (backup db)', NULL, 
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', NULL, NULL),
 
(NULL, 'NEW SAN SWITCH DELL DS6620B', NULL, NULL, NULL, 'L2-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 36, 1),

('SAN Switch', 'HA SAN SWITCH BROCADE G-720', '172.28.103.71', 2023, NULL, 'L2-01', NULL, NULL, 'HA SAN Switch', NULL, 
 'Update', 'End of Support', NULL, NULL, NULL, NULL, 'DRC', 37, 1),
 
('SAN Switch', 'BROCADE SAN SWITCH G-720', '?', 2022, NULL, 'L2-01', NULL, NULL, 'SAN Switch Untuk Storage', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 38, 1),
 
('SAN Switch', 'BROCADE SAN SWITCH G-720', '?', 2022, NULL, 'L2-01', NULL, NULL, 'SAN Switch Untuk Storage', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 39, 1), 
 
	#l22
('Storage', 'CONTROLLER STORAGE VSP E990', '172.28.103.118', 2021, NULL, 'L2-02', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 21, 4),
 
('Storage', 'DISK STORAGE VSP E990', NULL, 2021, NULL, 'L2-02', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 25, 2),
 
('Storage', 'DISK STORAGE VSP E990', NULL, 2021, NULL, 'L2-02', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 27, 2),
 
('Storage', 'DISK STORAGE VSP E990', NULL, 2021, NULL, 'L2-02', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 29, 2),
 
('Storage', 'HNAS 4060 STORAGE VSP 5100', NULL, 2022, NULL, 'L2-02', NULL, NULL, 'Storage Operasional', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 36, 2),
 
('Storage', 'HNAS 4060 STORAGE VSP 5100', NULL, 2022, NULL, 'L2-02', NULL, NULL, 'Storage Operasional', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 38, 2),
 
	#l23
('SERVER', 'HP PROLIANT DL 380 GEN 10', '172.28.103.29', 2022, NULL, 'L2-03', NULL, NULL, 'Storage Ceph', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 2, 2),
 
('SERVER', 'HP PROLIANT DL 380 GEN 10', '172.28.103.30', 2022, NULL, 'L2-03', NULL, NULL, 'Storage Ceph', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 4, 2),
 
('SERVER', 'HP PROLIANT DL 380 GEN 10', '172.28.103.31', 2022, NULL, 'L2-03', NULL, NULL, 'Storage Ceph', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 6, 2),
 
('SERVER', 'HP PROLIANT DL 380 GEN 10', '172.28.103.32', 2022, NULL, 'L2-03', NULL, NULL, 'Storage Ceph', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 8, 2),
 
('SERVER', 'HP PROLIANT DL 380 GEN 10', '172.28.103.33', 2022, NULL, 'L2-03', NULL, NULL, 'Storage Ceph', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 10, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.83 (Worker1)', 2022, 'J3048GE5', 'L2-05', NULL, NULL, 'Server Openshift', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 12, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.84 (Worker2)', 2022, 'J3048GE4', 'L2-05', NULL, NULL, 'Server Openshift', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 14, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.85 (Worker3)', 2022, 'J3048GE3', 'L2-05', NULL, NULL, 'Server Openshift', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 16, 2),
 
('Storage', 'STORAGE DB NETAPP AFF 800 (HA)', '172.28.103.209', 2023, NULL, 'L2-03', NULL, NULL, 'HA Storage DB', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 29, 4),
 
(NULL, 'MGMT SW OBJECT STORAGE', NULL, NULL, NULL, 'L2-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 35, 1),

('Switch', 'SWITCH HPE MELANOK CEPH', '172.28.103.27', 2022, NULL, 'L2-03', NULL, NULL, 'Switch Ceph', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 37, 1),
 
('Switch', 'SWITCH HPE MELANOK CEPH', '172.28.103.28', 2022, NULL, 'L2-03', NULL, NULL, 'Switch Ceph', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 38, 1),
 
 
	#l24
('Storage', 'QUANTUM SCALAR I6 (STORAGE TAPE)', '172.28.103.22 (Eth104/1/19)', 2022, NULL, 'L2-04', NULL, NULL, 'TAPE Library', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 1, 12),
 
('Storage', 'QUANTUM DX i4800 (DELL R 340) (MEDIA STORAGE)', '172.28.103.21 (Eth104/1/18)', 2022, NULL, 'L2-04', NULL, NULL, 'Media Disk', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 15, 2),
 
('Storage', 'QUANTUM DX i4800 (DELL R 340) (MEDIA STORAGE)', NULL, 2022, NULL, 'L2-04', NULL, NULL, 'Server Backup Disk', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 18, 2),
 
('Server', 'DELL POWER EDGE R 740 (COMVAULT)', '172.28.103.20 (Eth104/1/17)', 2022, NULL, 'L2-04', NULL, NULL, 'Server Backup Sistem Veem', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 20, 2),
 
('Storage', 'STORAGE VSP 5100', '172.28.103.174 -178', 2022, NULL, 'L2-04', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 24, 4),
 
('Storage', 'STORAGE VSP 5100', '172.28.103.174 -178', 2022, NULL, 'L2-04', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 28, 4),
 
('Switch', 'SWITCH STORAGE VSP 5100', NULL, 2022, NULL, 'L2-04', NULL, NULL, 'Switch Storage', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 32, 1),
 
('Switch', 'SWITCH STORAGE VSP 5100', NULL, 2022, NULL, 'L2-04', NULL, NULL, 'Switch Storage', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 33, 2),
 
('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL, 'L2-04', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 34, 2),
 
('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL, 'L2-04', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 36, 2),
 
('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL, 'L2-04', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 38, 2),
 
('Storage', 'DISK STORAGE VSP 5100', NULL, 2022, NULL, 'L2-04', NULL, NULL, 'Storage Database Operasional', NULL, 
 'Update', 'Support', 'IBS', NULL, NULL, NULL, 'DRC', 40, 2),
 

	#l25
('Server', 'LENOVO SR 650', '172.28.103.80 (Worker1)', 2022, 'J3048MRD', 'L2-05', NULL, NULL, 'Server Openshift & Openstack', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 1, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.81 (Worker2)', 2022, 'J3048MRH', 'L2-05', NULL, NULL, 'Server Openshift & Openstack', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 3, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.82 (Worker3)', 2022, 'J3048MRC', 'L2-05', NULL, NULL, 'Server Openshift & Openstack', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 5, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.86 (Compute1)', 2022, 'J3048GDV', 'L2-05', NULL, NULL, 'Server Maria DB (Next)', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 7, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.87 (Compute2)', 2022, 'J3048GE0', 'L2-05', NULL, NULL, 'Server Maria DB (Next)', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 9, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.88 (Compute3)', 2022, 'J3048GDX', 'L2-05', NULL, NULL, 'Server Openshift & Openstack', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 11, 2),
 
('Server', 'SERVER LENOVO SR 650 (CONTAINIRATION)', 'https://172.28.103.148', 2023, NULL, 'L2-05', NULL, NULL, 'SERVER LENOVO SR 650 (CONTAINIRATION)', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 13, 2),
 
('Server', 'SERVER LENOVO SR 650 (DATABASE PENDUKUNG)', '172.28.103.149', 2023, NULL, 'L2-05', NULL, NULL, 'SERVER LENOVO SR 650 (DATABASE PENDUKUNG)', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 15, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.95 (Report1) / IT Sec', 2022, 'J3048GE9', 'L2-05', NULL, NULL, 'Server Openshift & Openstack', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 19, 2),
 
('Server', 'LENOVO SR 650', '172.28.103.96 (Report2) / IT Sec', 2022, 'J3048GEA', 'L2-05', NULL, NULL, 'Server Openshift & Openstack', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 21, 2),
 
('Server', 'LENOVO SR 850', '172.28.103.97 (Dev/QA)', 2022, 'J304A6RE', 'L2-05', NULL, NULL, 'Server Openshift & Openstack', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 23, 2),
 
('Server', 'FUJITSU PRIME QUEST 3800 E2', 'http://172.28.103.202:8081/login.cgi', 2022, 'MBAG100036', 'L2-05', NULL, NULL, 'Server Aplikasi Virtualisasi', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 26, 7),
 
(NULL, NULL, '172.28.103.199, \nExsi : 172.28.103.207', NULL, NULL, 'L2-05', NULL, NULL, NULL, NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', NULL, NULL),
 
('Switch', 'SWITCH MELANOKS 1', '172.28.103.106', 2022, NULL, 'L2-05', NULL, NULL, 'Switch Openshift & Openstack', 'Single source PDU', 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 38, 1), 
 
('Switch', 'SWITCH MELANOKS 2', '172.28.103.107', 2022, NULL, 'L2-05', NULL, NULL, 'Switch Openshift & Openstack', 'Single source PDU', 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 37, 1),
 
 
	#l26
('Switch', 'APIC CONTROLLER 03 (APIC SERVER M3)', NULL, 2023, NULL, 'L2-06', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 1, 1),
 
('Switch', 'APIC CONTROLLER 02 (APIC SERVER M3)', NULL, 2023, NULL, 'L2-06', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 2, 1),
 
('Switch', 'APIC CONTROLLER 01 (APIC SERVER M3)', NULL, 2023, NULL, 'L2-06', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 3, 1),
 
(NULL, 'HP PROLIANT DL 380 GEN 10', NULL, NULL, NULL, 'L2-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 5, 2),

(NULL, 'MANAGEMENT SWITCH (C9300 L 48T 4X E)', NULL, NULL, NULL, 'L2-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 9,1),

(NULL, 'MANAGEMENT SWITCH (C9300 L 48T 4X E)', NULL, NULL, NULL, 'L2-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 11, 1),

(NULL, 'MANAGEMENT SWITCH (C9300 L 48T 4X E)', NULL, NULL, NULL, 'L2-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 12, 1),

(NULL, 'MANAGEMENT SWITCH (C9300 L 48T 4X E)', NULL, NULL, NULL, 'L2-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 14, 1),

(NULL, 'MANAGEMENT SWITCH (C9300 L 48T 4X E)', NULL, NULL, NULL, 'L2-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 15, 1),

('Switch', 'MANAGEMENT SWITCH (C9300 L 48T 4X E)', NULL, 2023, NULL, 'L2-06', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 17, 1),
 
('Switch', 'CORE 01 (C9500 48Y4C A)', NULL, 2023, NULL, 'L2-06', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 20, 1),
 
('Switch', 'CORE 02 (C9500 48Y4C A)', NULL, 2023, NULL, 'L2-06', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 18, 1),
 

	#l27	
('Switch', 'LEAF-103 (NEXUS 9K C93180YC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 3, 1),
 
('Switch', 'LEAF-123 (NEXUS 9K C93180YC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 5, 1 ),
 
('Switch', 'LEAF-122 (NEXUS 9K C93180YC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 6, 1),
 
('Switch', 'LEAF-121 (NEXUS 9K C93180YC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 8, 1),
 
('Switch', 'LEAF-106 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 9, 1),
 
('Switch', 'LEAF-105 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 11, 1),
 
('Switch', 'LEAF-104 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 12, 1),
 
('Switch', 'LEAF-103 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 14, 1),
 
('Switch', 'LEAF-102 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 15, 1),
 
('Switch', 'LEAF-101 (NEXUS 9K C93108TC FX)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 17, 1),
 
('Switch', 'SPINE-202 (NEXUS 9K C9332 C)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 18, 1),
 
('Switch', 'SPINE-201 (NEXUS 9K C9332 C)', NULL, 2023, NULL, 'L2-07', NULL, NULL, 'SPINE N LEAF', NULL, 
 'Update', 'Support', NULL, NULL, NULL, NULL, 'DRC', 20, 1),
 
	#l28
(NULL, 'CENTRAL PATCHING', NULL, NULL, NULL, 'L2-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DRC', 1, 40);


CREATE TABLE software(
	KODE_SUBS INT AUTO_INCREMENT PRIMARY KEY,
	NO_SUBS VARCHAR(20),
	NAMA_SUBS VARCHAR(255),
	KETERANGAN TEXT,
	TANGGAL_AWAL DATETIME,
	TANGGAL_BERAKHIR DATETIME,
	lokasi ENUM('Subscription','Perpetual') NOT NULL,
	partner VARCHAR(50));
	
INSERT INTO software (NO_SUBS,NAMA_SUBS,KETERANGAN,TANGGAL_AWAL,TANGGAL_BERAKHIR,lokasi)VALUES
(15516094,'Red Hat Integration, Premium, (16 Cores or 32 vCPUs)','Subscription 3Scale API Management (Openshift)',
 '2024-09-16 ','2026-03-14','subscription'),
 
(15516089,'Red Hat OpenShift Container Platform (Bare Metal Node), Premium (1-2 sockets up to 64 cores)','Subscription Worker Openshift',
 '2024-12-15 ','2025-12-14','subscription'),
 
(15560958,'Technical Account Management Services for Red Hat OpenShift Container Platform','Subscription Priority Support Red Hat (Openshift)',
 '2024-12-15','2025-12-14','subscription'),
 
(15516090,'Red Hat Enterprise Linux Server with Satellite, Premium (Physical or Virtual Nodes)','Subscription OS RHEL Bastion (Openshift)',
 '2024-09-16','2026-03-14','subscription'),
 
(15516095,'Red Hat Enterprise Linux Server with Satellite, Premium (Physical or Virtual Nodes)','Subscription OS RHEL Bastion (Openshift)',
 '2024-12-15','2025-12-15 ','subscription'),
  
(15516088,'Red Hat Integration, Premium, (16 Cores or 32 vCPUs)','Subscription 3Scale API Management (Openshift)',
 '2024-12-15 ','2025-12-14 ','subscription'),	
 
(16044752,'Red Hat Enterprise Linux Server, Premium (Physical or Virtual Nodes)','Subscription OS RHEL',
 '2025-01-01','2027-12-31','subscription'),
 
(4550244657,'LoadRunner Enterprise Web 2.0 Protocol Bundle Virtual User and Controller SW E-LTU','Subscription Support Load Runner Upgrade Patching',
 '2022-12-14','2023-12-13','subscription');