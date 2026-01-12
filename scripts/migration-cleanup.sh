#!/bin/bash

###############################################################################
# MIGRATION CONSOLIDATION HELPER SCRIPT
# Purpose: Analyze and help consolidate Laravel migrations
# Usage: bash scripts/migration-cleanup.sh
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Paths
MIGRATIONS_DIR="database/migrations"
BACKUP_DIR="backups/migrations_backup_$(date +%Y%m%d_%H%M%S)"

echo -e "${BLUE}╔════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   MIGRATION CONSOLIDATION HELPER SCRIPT                     ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════════╝${NC}"
echo ""

# Function to show menu
show_menu() {
    echo -e "${YELLOW}Choose an action:${NC}"
    echo "1) Analyze migration structure"
    echo "2) Backup current migrations"
    echo "3) List files to be deleted"
    echo "4) Dry run - Show what would be deleted"
    echo "5) Delete modification files (CAREFUL!)"
    echo "6) Generate consolidation report"
    echo "7) Validate PHP syntax"
    echo "8) Exit"
    echo ""
    read -p "Enter choice [1-8]: " choice
}

# Function: Analyze migrations
analyze_migrations() {
    echo -e "\n${BLUE}📊 ANALYZING MIGRATION STRUCTURE...${NC}\n"
    
    local total=0
    local create=0
    local modify=0
    local add=0
    local drop=0
    local increase=0
    local other=0
    
    for file in $MIGRATIONS_DIR/*.php; do
        total=$((total + 1))
        filename=$(basename "$file")
        
        if [[ $filename == *"create"* ]]; then
            create=$((create + 1))
            echo -e "${GREEN}✓${NC} CREATE: $filename"
        elif [[ $filename == *"modify"* ]] || [[ $filename == *"alter"* ]]; then
            modify=$((modify + 1))
            echo -e "${YELLOW}⚠${NC} MODIFY: $filename"
        elif [[ $filename == *"add"* ]]; then
            add=$((add + 1))
            echo -e "${YELLOW}⚠${NC} ADD:    $filename"
        elif [[ $filename == *"drop"* ]]; then
            drop=$((drop + 1))
            echo -e "${RED}✗${NC} DROP:   $filename"
        elif [[ $filename == *"increase"* ]]; then
            increase=$((increase + 1))
            echo -e "${YELLOW}⚠${NC} INCR:   $filename"
        else
            other=$((other + 1))
            echo -e "${BLUE}ℹ${NC} OTHER:  $filename"
        fi
    done
    
    echo ""
    echo -e "${BLUE}═══════════════════════════════════════════════════════════${NC}"
    echo -e "Total Files:        ${YELLOW}$total${NC}"
    echo -e "CREATE (Keep):      ${GREEN}$create${NC}"
    echo -e "MODIFY (Consol.):   ${YELLOW}$modify${NC}"
    echo -e "ADD (Consol.):      ${YELLOW}$add${NC}"
    echo -e "DROP (Consol.):     ${RED}$drop${NC}"
    echo -e "INCREASE (Consol.): ${YELLOW}$increase${NC}"
    echo -e "OTHER:              ${BLUE}$other${NC}"
    echo -e "${BLUE}═══════════════════════════════════════════════════════════${NC}"
    
    consolidate=$((modify + add + drop + increase))
    echo ""
    echo -e "${GREEN}✓ Can consolidate: $consolidate files${NC}"
    echo -e "${GREEN}✓ Reduction: $(echo "scale=1; $consolidate * 100 / $total" | bc)%${NC}"
}

# Function: Backup migrations
backup_migrations() {
    echo -e "\n${BLUE}💾 BACKING UP MIGRATIONS...${NC}\n"
    
    mkdir -p "$BACKUP_DIR"
    cp "$MIGRATIONS_DIR"/*.php "$BACKUP_DIR"/ 2>/dev/null
    
    echo -e "${GREEN}✓ Backup created: $BACKUP_DIR${NC}"
    echo "  Files backed up: $(ls -1 "$BACKUP_DIR" | wc -l)"
}

# Function: List files to delete
list_files_to_delete() {
    echo -e "\n${BLUE}📋 FILES TO BE DELETED (17 total)${NC}\n"
    
    local files_to_delete=(
        "2024_11_24_000001_add_assessment_columns_to_perilaku_table.php"
        "2024_11_24_000002_modify_id_perilaku_column.php"
        "2025_11_18_100454_make_id_absensi_nullable_in_laporan_perkembangan_table.php"
        "2025_11_19_073203_add_publikasi_to_pengumuman_table.php"
        "2025_11_19_080516_add_nip_to_guru_table.php"
        "2025_11_20_120445_alter_id_laporan_column_to_laporan_perkembangan_table.php"
        "2025_11_30_120818_add_hari_column_to_jadwal_table.php"
        "2025_11_30_122604_modify_absensi_table_for_pertemuan.php"
        "2025_11_30_122856_update_absensi_status_enum.php"
        "2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal.php"
        "2025_12_05_082950_add_laporan_and_parent_to_komentar_table.php"
        "2025_12_05_093200_make_id_orang_tua_nullable_in_komentar_table.php"
        "2025_12_07_125511_remove_waktu_column_from_jadwal_table.php"
        "2026_01_09_093637_increase_id_orang_tua_column_length.php"
        "2026_01_09_094635_increase_id_orang_tua_in_siswa_table.php"
        "2026_01_09_094834_increase_id_orang_tua_in_komentar_table.php"
        "2026_01_10_000000_expand_id_varchar_lengths.php"
    )
    
    count=0
    for file in "${files_to_delete[@]}"; do
        if [ -f "$MIGRATIONS_DIR/$file" ]; then
            echo -e "${RED}✗${NC} $file"
            count=$((count + 1))
        else
            echo -e "${YELLOW}?${NC} $file (NOT FOUND)"
        fi
    done
    
    echo ""
    echo -e "${BLUE}Files to delete: $count / ${#files_to_delete[@]}${NC}"
}

# Function: Dry run
dry_run() {
    echo -e "\n${BLUE}🔍 DRY RUN - WHAT WOULD BE DELETED${NC}\n"
    
    local files_to_delete=(
        "2024_11_24_000001_add_assessment_columns_to_perilaku_table.php"
        "2024_11_24_000002_modify_id_perilaku_column.php"
        "2025_11_18_100454_make_id_absensi_nullable_in_laporan_perkembangan_table.php"
        "2025_11_19_073203_add_publikasi_to_pengumuman_table.php"
        "2025_11_19_080516_add_nip_to_guru_table.php"
        "2025_11_20_120445_alter_id_laporan_column_to_laporan_perkembangan_table.php"
        "2025_11_30_120818_add_hari_column_to_jadwal_table.php"
        "2025_11_30_122604_modify_absensi_table_for_pertemuan.php"
        "2025_11_30_122856_update_absensi_status_enum.php"
        "2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal.php"
        "2025_12_05_082950_add_laporan_and_parent_to_komentar_table.php"
        "2025_12_05_093200_make_id_orang_tua_nullable_in_komentar_table.php"
        "2025_12_07_125511_remove_waktu_column_from_jadwal_table.php"
        "2026_01_09_093637_increase_id_orang_tua_column_length.php"
        "2026_01_09_094635_increase_id_orang_tua_in_siswa_table.php"
        "2026_01_09_094834_increase_id_orang_tua_in_komentar_table.php"
        "2026_01_10_000000_expand_id_varchar_lengths.php"
    )
    
    local would_delete=0
    for file in "${files_to_delete[@]}"; do
        if [ -f "$MIGRATIONS_DIR/$file" ]; then
            echo -e "${RED}rm${NC} $MIGRATIONS_DIR/$file"
            would_delete=$((would_delete + 1))
        fi
    done
    
    echo ""
    echo -e "${BLUE}Would delete: $would_delete files${NC}"
    echo -e "${YELLOW}Remaining files: $(( $(ls -1 $MIGRATIONS_DIR/*.php | wc -l) - would_delete ))${NC}"
}

# Function: Delete files
delete_files() {
    echo -e "\n${RED}⚠️  DELETING FILES - THIS CANNOT BE UNDONE!${NC}\n"
    read -p "Are you sure? Type 'YES I UNDERSTAND' to confirm: " confirm
    
    if [ "$confirm" != "YES I UNDERSTAND" ]; then
        echo -e "${YELLOW}Cancelled.${NC}"
        return
    fi
    
    local files_to_delete=(
        "2024_11_24_000001_add_assessment_columns_to_perilaku_table.php"
        "2024_11_24_000002_modify_id_perilaku_column.php"
        "2025_11_18_100454_make_id_absensi_nullable_in_laporan_perkembangan_table.php"
        "2025_11_19_073203_add_publikasi_to_pengumuman_table.php"
        "2025_11_19_080516_add_nip_to_guru_table.php"
        "2025_11_20_120445_alter_id_laporan_column_to_laporan_perkembangan_table.php"
        "2025_11_30_120818_add_hari_column_to_jadwal_table.php"
        "2025_11_30_122604_modify_absensi_table_for_pertemuan.php"
        "2025_11_30_122856_update_absensi_status_enum.php"
        "2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal.php"
        "2025_12_05_082950_add_laporan_and_parent_to_komentar_table.php"
        "2025_12_05_093200_make_id_orang_tua_nullable_in_komentar_table.php"
        "2025_12_07_125511_remove_waktu_column_from_jadwal_table.php"
        "2026_01_09_093637_increase_id_orang_tua_column_length.php"
        "2026_01_09_094635_increase_id_orang_tua_in_siswa_table.php"
        "2026_01_09_094834_increase_id_orang_tua_in_komentar_table.php"
        "2026_01_10_000000_expand_id_varchar_lengths.php"
    )
    
    local deleted=0
    for file in "${files_to_delete[@]}"; do
        if [ -f "$MIGRATIONS_DIR/$file" ]; then
            rm "$MIGRATIONS_DIR/$file"
            echo -e "${GREEN}✓${NC} Deleted: $file"
            deleted=$((deleted + 1))
        fi
    done
    
    echo ""
    echo -e "${GREEN}✓ Deleted $deleted files${NC}"
    echo -e "${BLUE}Remaining migration files: $(ls -1 $MIGRATIONS_DIR/*.php | wc -l)${NC}"
}

# Function: Generate report
generate_report() {
    echo -e "\n${BLUE}📄 GENERATING CONSOLIDATION REPORT${NC}\n"
    
    local report_file="MIGRATION_CONSOLIDATION_REPORT_$(date +%Y%m%d_%H%M%S).txt"
    
    {
        echo "MIGRATION CONSOLIDATION REPORT"
        echo "Generated: $(date)"
        echo ""
        echo "BEFORE:"
        echo "- Total migration files: 34"
        echo "- CREATE files: 14"
        echo "- MODIFY/ADD/ALTER/INCREASE files: 20"
        echo ""
        echo "AFTER:"
        echo "- Total migration files: 17"
        echo "- CREATE files (consolidated): 14"
        echo "- NEW TABLE files (kept): 3"
        echo ""
        echo "CONSOLIDATED CHANGES:"
        echo "- Assessment columns added to perilaku"
        echo "- NIP column added to guru"
        echo "- Publikasi column added to pengumuman"
        echo "- Hari, waktu_mulai, waktu_selesai added to jadwal"
        echo "- All ID varchar lengths increased (+3 digits)"
        echo "- Absensi relationships restructured"
        echo "- Komentar relationships expanded"
        echo "- Laporan_perkembangan id_absensi made nullable"
        echo ""
        echo "FILES DELETED:"
        echo "- 2024_11_24_000001_add_assessment_columns_to_perilaku_table.php"
        echo "- 2024_11_24_000002_modify_id_perilaku_column.php"
        echo "- (... 15 more files)"
        echo ""
        echo "MIGRATION STATUS BEFORE:"
        php artisan migrate:status 2>/dev/null || echo "Could not retrieve status"
        echo ""
        echo "FILES IN DIRECTORY:"
        ls -lh $MIGRATIONS_DIR/*.php
        
    } > "$report_file"
    
    echo -e "${GREEN}✓ Report generated: $report_file${NC}"
}

# Function: Validate syntax
validate_syntax() {
    echo -e "\n${BLUE}✓ VALIDATING PHP SYNTAX${NC}\n"
    
    local errors=0
    local total=0
    
    for file in $MIGRATIONS_DIR/*.php; do
        total=$((total + 1))
        if ! php -l "$file" > /dev/null 2>&1; then
            echo -e "${RED}✗ SYNTAX ERROR: $(basename $file)${NC}"
            errors=$((errors + 1))
        fi
    done
    
    echo ""
    if [ $errors -eq 0 ]; then
        echo -e "${GREEN}✓ All $total files have valid PHP syntax${NC}"
    else
        echo -e "${RED}✗ Found $errors syntax errors out of $total files${NC}"
    fi
}

# Main loop
while true; do
    show_menu
    
    case $choice in
        1) analyze_migrations ;;
        2) backup_migrations ;;
        3) list_files_to_delete ;;
        4) dry_run ;;
        5) delete_files ;;
        6) generate_report ;;
        7) validate_syntax ;;
        8) 
            echo -e "\n${BLUE}Goodbye!${NC}\n"
            exit 0
            ;;
        *)
            echo -e "${RED}Invalid option. Please try again.${NC}"
            ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
done
